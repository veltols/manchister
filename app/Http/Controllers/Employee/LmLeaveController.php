<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrLeave;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class LmLeaveController extends Controller
{
    /** Ensure current user is a Line Manager for at least one department. */
    private function getLmEmployeeId()
    {
        $user = Auth::user();
        if (!$user->employee) abort(403, 'No employee profile.');
        return $user->employee->employee_id;
    }

    /**
     * LM Leave Queue — all leaves pending this manager's decision.
     */
    public function index(Request $request)
    {
        $employeeId = $this->getLmEmployeeId();
        
        // Verify this person is actually a line manager
        $isDeptManager = Department::where('line_manager_id', $employeeId)->exists();
        if (!$isDeptManager) abort(403, 'You are not assigned as a Line Manager for any department.');

        $statusFilter = strtolower($request->input('status', 'pending')); // pending | all

        $query = HrLeave::with(['employee.department', 'employee.designation', 'type'])
            ->where('line_manager_id', $employeeId)
            ->orderBy('submission_date', 'asc');

        if ($statusFilter === 'pending') {
            $query->whereIn('leave_status_id', [
                HrLeave::STATUS_PENDING,          // status=1 with LM assigned
                HrLeave::STATUS_PENDING_APPROVAL, // status=2 standard LM queue
            ]);
        }

        $leaves = $query->paginate(15);

        $pendingStatuses = [HrLeave::STATUS_PENDING, HrLeave::STATUS_PENDING_APPROVAL];
        $stats = [
            'pending'  => HrLeave::where('line_manager_id', $employeeId)->whereIn('leave_status_id', $pendingStatuses)->count(),
            'approved' => HrLeave::where('line_manager_id', $employeeId)->where('leave_status_id', HrLeave::STATUS_PENDING_GM)->count(),
            'rejected' => HrLeave::where('line_manager_id', $employeeId)->where('leave_status_id', HrLeave::STATUS_REJECTED)->count(),
            'total'    => HrLeave::where('line_manager_id', $employeeId)->count(),
        ];

        return view('emp.lm_leaves.index', compact('leaves', 'stats', 'statusFilter'));
    }

    /**
     * LM approves — forwards to GM (STATUS_PENDING_GM).
     */
    public function approve(Request $request, $id)
    {
        $employeeId = $this->getLmEmployeeId();
        $leave = HrLeave::with(['employee', 'type'])->findOrFail($id);

        // Security: only the assigned LM can act
        if ((int) $leave->line_manager_id !== (int) $employeeId) {
            return redirect()->back()->with('error', 'Unauthorized — this leave is not in your queue.');
        }

        if (!in_array((int) $leave->leave_status_id, [HrLeave::STATUS_PENDING, HrLeave::STATUS_PENDING_APPROVAL])) {
            return redirect()->back()->with('error', 'This leave request is not pending your review.');
        }

        // Find GM
        $gm   = \App\Models\User::where('is_gm', 1)->where('is_active', 1)->first();
        $gmId = $gm ? $gm->user_id : null;

        $leave->leave_status_id = HrLeave::STATUS_PENDING_GM;
        $leave->lm_comments     = $request->input('lm_comments');
        $leave->lm_reviewed_at  = now();
        $leave->gm_id           = $gmId;
        $leave->save();

        \App\Models\SystemLog::create([
            'related_table' => 'hr_employees_leaves',
            'related_id'    => $leave->leave_id,
            'log_date'      => now(),
            'log_action'    => 'Manager_Approved',
            'log_remark'    => 'Approved by Line Manager. Forwarded to GM for final decision.',
            'logger_type'   => 'employees_list',
            'logged_by'     => Auth::user()->user_id,
            'log_type'      => 'int',
        ]);

        // Notify GM
        if ($gm) {
            $empName = optional($leave->employee)->full_name ?? 'Employee';
            \App\Services\NotificationService::send(
                "Leave #{$leave->leave_id} for {$empName} approved by Line Manager — awaiting your final decision.",
                "admin/leaves/gm",
                $gm->user_id
            );
        }

        // Notify Employee
        $typeName = optional($leave->type)->leave_type_name ?? 'Leave';
        \App\Services\NotificationService::send(
            "Your {$typeName} request has been reviewed by your Line Manager and forwarded to the GM for final approval.",
            "emp/leaves",
            $leave->employee_id
        );

        return redirect()->route('emp.lm.leaves.index')
            ->with('success', "Leave #{$leave->leave_id} approved and forwarded to GM.");
    }

    /**
     * LM rejects — final rejection (STATUS_REJECTED).
     */
    public function reject(Request $request, $id)
    {
        $request->validate(['lm_comments' => 'required|string|min:5']);

        $employeeId = $this->getLmEmployeeId();
        $leave = HrLeave::with(['employee', 'type'])->findOrFail($id);

        if ((int) $leave->line_manager_id !== (int) $employeeId) {
            return redirect()->back()->with('error', 'Unauthorized — this leave is not in your queue.');
        }

        if (!in_array((int) $leave->leave_status_id, [HrLeave::STATUS_PENDING, HrLeave::STATUS_PENDING_APPROVAL])) {
            return redirect()->back()->with('error', 'This leave request is not pending your review.');
        }

        $leave->leave_status_id = HrLeave::STATUS_REJECTED;
        $leave->lm_comments     = $request->lm_comments;
        $leave->lm_reviewed_at  = now();
        $leave->save();

        \App\Models\SystemLog::create([
            'related_table' => 'hr_employees_leaves',
            'related_id'    => $leave->leave_id,
            'log_date'      => now(),
            'log_action'    => 'Manager_Rejected',
            'log_remark'    => $request->lm_comments,
            'logger_type'   => 'employees_list',
            'logged_by'     => Auth::user()->user_id,
            'log_type'      => 'int',
        ]);

        // Notify Employee
        $typeName = optional($leave->type)->leave_type_name ?? 'Leave';
        \App\Services\NotificationService::send(
            "Your {$typeName} request was rejected by your Line Manager. Reason: {$request->lm_comments}",
            "emp/leaves",
            $leave->employee_id
        );

        return redirect()->route('emp.lm.leaves.index')
            ->with('success', "Leave #{$leave->leave_id} rejected.");
    }
}
