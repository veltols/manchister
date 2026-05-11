<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OutboundActionItem;
use App\Models\SystemLog;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class OutboundActionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee->employee_id ?? 0;

        $tasks = OutboundActionItem::with(['communication.type', 'assignedBy'])
            ->where('assigned_to_id', $employeeId)
            ->orderBy('due_date', 'asc')
            ->get();

        return view('emp.communications.outbound_tasks.index', compact('tasks'));
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $employeeId = $user->employee->employee_id ?? 0;

        $task = OutboundActionItem::where('assigned_to_id', $employeeId)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
            'completion_note' => 'nullable|string',
        ]);

        $task->update([
            'status' => $request->status,
            'completion_note' => $request->completion_note,
        ]);

        SystemLog::create([
            'related_table' => 'outbound_action_items',
            'related_id' => $task->action_id,
            'log_action' => 'Task_Status_Update',
            'log_remark' => 'Employee updated task status to: ' . $request->status,
            'log_date' => now(),
            'logged_by' => $employeeId,
            'logger_type' => 'employees_list',
            'log_type' => 'int',
        ]);

        // Notify GM if completed
        if ($request->status === 'Completed') {
            $gm = User::where('is_gm', 1)->first(); // Not ideal if multiple GMs, but works for now
            if ($gm) {
                NotificationService::send(
                    "Action Item for " . $task->communication->communication_code . " completed by " . $user->employee->employee_name,
                    "admin/communications/outbound/" . $task->communication_id,
                    $gm->employee_id ?? $gm->user_id
                );
            }
        }

        return redirect()->back()->with('success', 'Task status updated successfully.');
    }
}
