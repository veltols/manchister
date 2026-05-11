<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommunicationRequest;
use App\Models\OutboundActionItem;
use App\Models\Employee;
use App\Models\SystemLog;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class OutboundGmController extends Controller
{
    protected function ensureGm()
    {
        $user = Auth::user();
        if (!$user || !$user->is_gm) {
            abort(403, 'Access denied. Only the General Manager can access this module.');
        }
        return $user->employee->employee_id ?? $user->user_id;
    }

    public function index(Request $request)
    {
        $this->ensureGm();

        $query = CommunicationRequest::with(['employee', 'type', 'status'])
            ->where('is_approved_1', 1) // Must be approved by LM first
            ->orderBy('requested_date', 'desc');

        if ($request->filled('status')) {
            $query->where('communication_status_id', $request->status);
        } else {
            $query->where('is_approved_2', 0); // Default to pending GM review
        }

        $records = $query->paginate(15);

        return view('admin.communications.outbound.index', compact('records'));
    }

    public function show($id)
    {
        $this->ensureGm();

        $record = CommunicationRequest::with([
            'employee.department', 'attachments', 'actionItems.assignedTo', 'type'
        ])->findOrFail($id);
 
        $employees = Employee::whereHas('systemUser', function($q) {
            $q->where('is_active', 1);
        })->orderBy('first_name', 'asc')->get();
 
        return view('admin.communications.outbound.show', compact('record', 'employees'));
    }

    public function decide(Request $request, $id)
    {
        $gmId = $this->ensureGm();

        $request->validate([
            'decision' => 'required|in:approved,rejected,modifications_required',
            'notes' => 'nullable|string',
        ]);

        $record = CommunicationRequest::findOrFail($id);

        $statusMap = [
            'approved' => 1,
            'rejected' => 2,
            'modifications_required' => 3,
        ];

        $record->is_approved_2 = $statusMap[$request->decision];
        $record->approved_2_date = time();
        $record->approved_2_notes = $request->notes;

        if ($request->decision === 'approved') {
            $record->communication_status_id = 3; // Ready for Liaison (Form 2)
            // Assign to Liaison Officers (anyone with is_liaison = 1)
            $liaisons = User::where('is_liaison', 1)->get();
            foreach ($liaisons as $l) {
                NotificationService::send(
                    "Outbound Communication REF: " . $record->communication_code . " approved by GM. Please finalize dispatch (Form 2).",
                    "emp/communications/show/" . $record->communication_id, // Link to show or liaison finalization view
                    $l->employee_id ?? $l->user_id
                );
            }
        } elseif ($request->decision === 'modifications_required') {
            $record->modification_notes = $request->notes;
            NotificationService::send(
                "GM requested modifications for Outbound Comm REF: " . $record->communication_code,
                "emp/communications/show/" . $record->communication_id,
                $record->requested_by
            );
        } else {
            $record->communication_status_id = 5; // Rejected
            NotificationService::send(
                "Your Outbound Comm request REF: " . $record->communication_code . " was rejected by the GM.",
                "emp/communications/show/" . $record->communication_id,
                $record->requested_by
            );
        }

        $record->save();

        SystemLog::create([
            'related_table' => 'm_communications_list',
            'related_id' => $record->communication_id,
            'log_action' => 'GM_Decision_' . $request->decision,
            'log_remark' => $request->notes ?? 'GM made a decision on the request.',
            'log_date' => now(),
            'logged_by' => $gmId,
            'logger_type' => 'employees_list',
            'log_type' => 'int',
        ]);

        return redirect()->route('admin.communications.outbound.index')
            ->with('success', 'Decision recorded successfully.');
    }

    public function storeActionItem(Request $request, $id)
    {
        $gmId = $this->ensureGm();

        $request->validate([
            'assigned_to_id' => 'required|exists:employees_list,employee_id',
            'action_required' => 'required|string',
            'due_date' => 'required|date',
        ]);

        $record = CommunicationRequest::findOrFail($id);

        $action = OutboundActionItem::create([
            'communication_id' => $record->communication_id,
            'assigned_by_id' => $gmId,
            'assigned_to_id' => $request->assigned_to_id,
            'action_required' => $request->action_required,
            'due_date' => $request->due_date,
            'status' => 'Pending',
        ]);

        NotificationService::send(
            "New Action Item assigned for Outbound Comm REF: " . $record->communication_code . ". Action: " . $request->action_required,
            "emp/communications/show/" . $record->communication_id,
            $request->assigned_to_id
        );

        SystemLog::create([
            'related_table' => 'outbound_action_items',
            'related_id' => $action->action_id,
            'log_action' => 'Action_Item_Assigned',
            'log_remark' => 'GM assigned task to employee ID: ' . $request->assigned_to_id,
            'log_date' => now(),
            'logged_by' => $gmId,
            'logger_type' => 'employees_list',
            'log_type' => 'int',
        ]);

        return redirect()->back()->with('success', 'Action item assigned successfully.');
    }

    public function destroyActionItem($id, $actionId)
    {
        $this->ensureGm();
        OutboundActionItem::where('communication_id', $id)->where('action_id', $actionId)->delete();
        return redirect()->back()->with('success', 'Action item removed.');
    }
}
