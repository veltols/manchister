<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommunicationRequest;
use App\Models\Department;
use App\Models\SystemLog;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class LmCommunicationController extends Controller
{
    private function getLmEmployeeId()
    {
        $user = Auth::user();
        if (!$user->employee) abort(403, 'No employee profile.');
        return $user->employee->employee_id;
    }

    public function index(Request $request)
    {
        $employeeId = $this->getLmEmployeeId();
        
        $statusFilter = strtolower($request->input('status', 'pending'));

        $query = CommunicationRequest::with(['type', 'status', 'employee.department'])
            ->where('approval_id_1', $employeeId)
            ->orderBy('requested_date', 'asc');

        if ($statusFilter === 'pending') {
            $query->where('is_approved_1', 0);
        }

        $records = $query->paginate(15);

        $stats = [
            'pending'  => CommunicationRequest::where('approval_id_1', $employeeId)->where('is_approved_1', 0)->count(),
            'approved' => CommunicationRequest::where('approval_id_1', $employeeId)->where('is_approved_1', 1)->count(),
            'rejected' => CommunicationRequest::where('approval_id_1', $employeeId)->where('is_approved_1', 2)->count(),
            'total'    => CommunicationRequest::where('approval_id_1', $employeeId)->count(),
        ];

        return view('emp.lm_communications.index', compact('records', 'stats', 'statusFilter'));
    }

    public function approve(Request $request, $id)
    {
        $employeeId = $this->getLmEmployeeId();
        $comm = CommunicationRequest::findOrFail($id);

        if ((int) $comm->approval_id_1 !== (int) $employeeId) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Find GM for the next stage (Form 1)
        $gm = User::where('is_gm', 1)->where('is_active', 1)->first();
        $gmId = $gm ? $gm->employee_id ?? $gm->user_id : null; // Preference to employee_id if exists

        $comm->is_approved_1 = 1;
        $comm->approved_1_date = now();
        $comm->approved_1_notes = $request->input('notes');
        $comm->approval_id_2 = $gmId; // Assign to GM
        $comm->communication_status_id = 2; // Under Review / Manager Approved
        $comm->save();

        SystemLog::create([
            'related_table' => 'm_communications_list',
            'related_id' => $comm->communication_id,
            'log_action' => 'Manager_Approved',
            'log_remark' => 'Approved by Line Manager. Forwarded to GM (Form 1).',
            'log_date' => now(),
            'logged_by' => $employeeId,
            'logger_type' => 'employees_list',
            'log_type' => 'int',
        ]);

        if ($gmId) {
            NotificationService::send(
                "New Outbound Comm Request (Form 1) awaiting GM review, REF: " . $comm->communication_code,
                "admin/communications/gm", 
                $gmId
            );
        }

        return redirect()->back()->with('success', 'Request approved and forwarded to GM.');
    }

    public function modify(Request $request, $id)
    {
        $request->validate(['notes' => 'required|string']);
        
        $employeeId = $this->getLmEmployeeId();
        $comm = CommunicationRequest::findOrFail($id);

        if ((int) $comm->approval_id_1 !== (int) $employeeId) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $comm->is_approved_1 = 3; // Modification Requested
        $comm->approved_1_notes = $request->notes;
        $comm->modification_notes = $request->notes;
        $comm->save();

        SystemLog::create([
            'related_table' => 'm_communications_list',
            'related_id' => $comm->communication_id,
            'log_action' => 'Modification_Requested',
            'log_remark' => 'Line Manager requested modification: ' . $request->notes,
            'log_date' => now(),
            'logged_by' => $employeeId,
            'logger_type' => 'employees_list',
            'log_type' => 'int',
        ]);

        NotificationService::send(
            "Your Comm Request REF: " . $comm->communication_code . " requires modification. Notes: " . $request->notes,
            "emp/communications/show/" . $comm->communication_id,
            $comm->requested_by
        );

        return redirect()->back()->with('success', 'Modification request sent to employee.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['notes' => 'required|string']);

        $employeeId = $this->getLmEmployeeId();
        $comm = CommunicationRequest::findOrFail($id);

        if ((int) $comm->approval_id_1 !== (int) $employeeId) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $comm->is_approved_1 = 2; // Rejected
        $comm->approved_1_notes = $request->notes;
        $comm->communication_status_id = 5; // Rejected status
        $comm->save();

        SystemLog::create([
            'related_table' => 'm_communications_list',
            'related_id' => $comm->communication_id,
            'log_action' => 'Manager_Rejected',
            'log_remark' => 'Rejected by Line Manager: ' . $request->notes,
            'log_date' => now(),
            'logged_by' => $employeeId,
            'logger_type' => 'employees_list',
            'log_type' => 'int',
        ]);

        NotificationService::send(
            "Your Outbound Comm Request REF: " . $comm->communication_code . " was rejected by your Line Manager.",
            "emp/communications/show/" . $comm->communication_id,
            $comm->requested_by
        );

        return redirect()->back()->with('success', 'Request rejected.');
    }
}
