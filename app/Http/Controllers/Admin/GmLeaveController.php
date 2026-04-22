<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrLeave;
use Illuminate\Support\Facades\Auth;

class GmLeaveController extends Controller
{
    /**
     * GM Leave Queue — all leaves forwarded by Line Manager awaiting GM decision.
     */
    public function index(Request $request)
    {
        $gm   = Auth::user();
        if (!$gm->is_gm) abort(403, 'Access denied. GM role required.');

        $query = HrLeave::with(['employee.department', 'employee.designation', 'type', 'lineManager'])
            ->where('leave_status_id', HrLeave::STATUS_PENDING_GM)
            ->orderBy('lm_reviewed_at', 'asc');

        $leaves = $query->paginate(15);

        $stats = [
            'pending_gm'  => HrLeave::where('leave_status_id', HrLeave::STATUS_PENDING_GM)->count(),
            'approved'    => HrLeave::where('leave_status_id', HrLeave::STATUS_APPROVED)->where('gm_id', $gm->user_id)->count(),
            'rejected'    => HrLeave::where('leave_status_id', HrLeave::STATUS_REJECTED)->where('gm_id', $gm->user_id)->count(),
        ];

        return view('admin.leaves.gm_index', compact('leaves', 'stats'));
    }

    /**
     * GM approves a leave — final approval.
     */
    public function approve(Request $request, $id)
    {
        $gm = Auth::user();
        if (!$gm->is_gm) abort(403);

        $leave = HrLeave::with(['employee', 'type'])->findOrFail($id);

        if ($leave->leave_status_id !== HrLeave::STATUS_PENDING_GM) {
            return redirect()->back()->with('error', 'This leave is not awaiting GM decision.');
        }

        $leave->leave_status_id = HrLeave::STATUS_APPROVED;
        $leave->gm_comments     = $request->input('gm_comments');
        $leave->gm_reviewed_at  = now();
        $leave->gm_id           = $gm->user_id;
        $leave->save();

        \App\Models\SystemLog::create([
            'related_table' => 'hr_employees_leaves',
            'related_id'    => $leave->leave_id,
            'log_date'      => now(),
            'log_action'    => 'GM_Approved',
            'log_remark'    => 'Final approval by GM. ' . ($request->gm_comments ?? ''),
            'logger_type'   => 'employees_list',
            'logged_by'     => $gm->user_id,
            'log_type'      => 'int',
        ]);

        // Notify Employee
        $typeName = optional($leave->type)->leave_type_name ?? 'Leave';
        \App\Services\NotificationService::send(
            "🎉 Your {$typeName} request has been APPROVED by the General Manager.",
            "emp/leaves",
            $leave->employee_id
        );

        // Notify HR
        $empName = optional($leave->employee)->full_name ?? 'Employee';
        $hrUsers = \App\Models\User::where('user_type', 'hr')->where('is_active', 1)->get();
        foreach ($hrUsers as $hr) {
            \App\Services\NotificationService::send(
                "Leave #{$leave->leave_id} for {$empName} has been approved by the GM.",
                "hr/leaves",
                $hr->user_id
            );
        }

        return redirect()->back()->with('success', 'Leave approved successfully.');
    }

    /**
     * GM rejects a leave — final rejection.
     */
    public function reject(Request $request, $id)
    {
        $request->validate(['gm_comments' => 'required|string|min:5']);

        $gm = Auth::user();
        if (!$gm->is_gm) abort(403);

        $leave = HrLeave::with(['employee', 'type'])->findOrFail($id);

        if ($leave->leave_status_id !== HrLeave::STATUS_PENDING_GM) {
            return redirect()->back()->with('error', 'This leave is not awaiting GM decision.');
        }

        $leave->leave_status_id = HrLeave::STATUS_REJECTED;
        $leave->gm_comments     = $request->gm_comments;
        $leave->gm_reviewed_at  = now();
        $leave->gm_id           = $gm->user_id;
        $leave->save();

        \App\Models\SystemLog::create([
            'related_table' => 'hr_employees_leaves',
            'related_id'    => $leave->leave_id,
            'log_date'      => now(),
            'log_action'    => 'GM_Rejected',
            'log_remark'    => "GM rejection: {$request->gm_comments}",
            'logger_type'   => 'employees_list',
            'logged_by'     => $gm->user_id,
            'log_type'      => 'int',
        ]);

        // Notify Employee
        $typeName = optional($leave->type)->leave_type_name ?? 'Leave';
        \App\Services\NotificationService::send(
            "Your {$typeName} request was rejected by the General Manager. Reason: {$request->gm_comments}",
            "emp/leaves",
            $leave->employee_id
        );

        // Notify HR
        $empName = optional($leave->employee)->full_name ?? 'Employee';
        $hrUsers = \App\Models\User::where('user_type', 'hr')->where('is_active', 1)->get();
        foreach ($hrUsers as $hr) {
            \App\Services\NotificationService::send(
                "Leave #{$leave->leave_id} for {$empName} has been rejected by the GM.",
                "hr/leaves",
                $hr->user_id
            );
        }

        return redirect()->back()->with('success', 'Leave rejected.');
    }
}
