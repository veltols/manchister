<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrLeave;
use App\Models\LeaveType;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $search = $request->input('search'); // Ref No
        $typeId = $request->input('type_id');
        $statusId = $request->input('status_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = HrLeave::with(['employee', 'type'])
            ->orderBy('leave_id', 'desc');

        // Apply Filters
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        if ($search) {
            $query->where('leave_id', 'LIKE', "%$search%");
        }
        if ($typeId) {
            $query->where('leave_type_id', $typeId);
        }
        if ($statusId) {
            $query->where('leave_status_id', $statusId);
        }
        if ($startDate) {
            $query->whereDate('start_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('start_date', '<=', $endDate);
        }

        $leaves = $query->paginate(15);

        $employees = Employee::where('is_deleted', 0)
            ->where('is_hidden', 0)
             ->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('first_name')
            ->get();

        $types = LeaveType::all();

        // Calculate Stats based on current filtered query
        // Re-create query for stats to avoid pagination but keep filters
        $statsQuery = HrLeave::query();
        if ($employeeId) $statsQuery->where('employee_id', $employeeId);
        if ($search) $statsQuery->where('leave_id', 'LIKE', "%$search%");
        if ($typeId) $statsQuery->where('leave_type_id', $typeId);
        if ($statusId) $statsQuery->where('leave_status_id', $statusId);
        if ($startDate) $statsQuery->whereDate('start_date', '>=', $startDate);
        if ($endDate) $statsQuery->whereDate('start_date', '<=', $endDate);

        $stats = [
            'pending' => (clone $statsQuery)->where('leave_status_id', HrLeave::STATUS_PENDING)->count(),
            'approved' => (clone $statsQuery)->where('leave_status_id', HrLeave::STATUS_APPROVED)->count(),
            'rejected' => (clone $statsQuery)->where('leave_status_id', HrLeave::STATUS_REJECTED)->count(),
            'total' => $statsQuery->count()
        ];

        return view('hr.leaves.index', compact('leaves', 'employees', 'types', 'stats'));
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $employeeId = $request->input('employee_id');
        $search = $request->input('search');
        $typeId = $request->input('type_id');
        $statusId = $request->input('status_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = HrLeave::with(['employee', 'type'])
            ->orderBy('leave_id', 'desc');

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        if ($search) {
            $query->where('leave_id', 'LIKE', "%$search%");
        }
        if ($typeId) {
            $query->where('leave_type_id', $typeId);
        }
        if ($statusId) {
            $query->where('leave_status_id', $statusId);
        }
        if ($startDate) {
            $query->whereDate('start_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('start_date', '<=', $endDate);
        }

        $leaves = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $leaves->items(),
            'pagination' => [
                'current_page' => $leaves->currentPage(),
                'last_page' => $leaves->lastPage(),
                'per_page' => $leaves->perPage(),
                'total' => $leaves->total(),
                'from' => $leaves->firstItem(),
                'to' => $leaves->lastItem(),
            ]
        ]);
    }

    public function exportCsv(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $search     = $request->input('search');
        $typeId     = $request->input('type_id');
        $statusId   = $request->input('status_id');
        $startDate  = $request->input('start_date');
        $endDate    = $request->input('end_date');

        $query = HrLeave::with(['employee', 'type'])
            ->orderBy('leave_id', 'desc');

        if ($employeeId) $query->where('employee_id', $employeeId);
        if ($search)     $query->where('leave_id', 'LIKE', "%$search%");
        if ($typeId)     $query->where('leave_type_id', $typeId);
        if ($statusId)   $query->where('leave_status_id', $statusId);
        if ($startDate)  $query->whereDate('start_date', '>=', $startDate);
        if ($endDate)    $query->whereDate('start_date', '<=', $endDate);

        $leaves = $query->get();

        $statusLabels = [
            HrLeave::STATUS_PENDING          => 'Pending Manager',
            HrLeave::STATUS_PENDING_APPROVAL => 'With Manager',
            HrLeave::STATUS_PENDING_GM       => 'Pending GM',
            HrLeave::STATUS_APPROVED         => 'Approved',
            HrLeave::STATUS_REJECTED         => 'Rejected',
            HrLeave::STATUS_ACTION_REQUIRED  => 'Pending Employee',
        ];

        $filename = 'leaves_export_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($leaves, $statusLabels) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            fputcsv($handle, [
                'Ref #',
                'Employee Name',
                'Leave Type',
                'Start Date',
                'End Date',
                'Total Days',
                'Status',
                'Remarks',
                'Submission Date',
            ]);

            foreach ($leaves as $leave) {
                $employeeName = $leave->employee
                    ? $leave->employee->first_name . ' ' . $leave->employee->last_name
                    : 'Unknown (' . $leave->employee_id . ')';

                fputcsv($handle, [
                    $leave->leave_id,
                    $employeeName,
                    $leave->type->leave_type_name ?? 'N/A',
                    $leave->start_date ? $leave->start_date->format('Y-m-d') : '-',
                    $leave->end_date   ? $leave->end_date->format('Y-m-d')   : '-',
                    $leave->total_days ?? 0,
                    $statusLabels[$leave->leave_status_id] ?? 'Unknown',
                    $leave->leave_remarks ?? '',
                    $leave->submission_date ? \Carbon\Carbon::parse($leave->submission_date)->format('Y-m-d') : '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        $employees = Employee::where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->orderBy('first_name')
            ->get();

        $types = LeaveType::all();

        return view('hr.leaves.create', compact('employees', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees_list,employee_id',
            'leave_type_id' => 'required|exists:hr_employees_leave_types,leave_type_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'leave_remarks' => 'required|string',
            'leave_attachment' => 'nullable|file|max:8192',
        ]);

        $leave = new HrLeave();
        $leave->employee_id = $request->employee_id;
        $leave->leave_type_id = $request->leave_type_id;
        $leave->start_date = $request->start_date;
        $leave->end_date = $request->end_date;
        $leave->leave_remarks = $request->leave_remarks;
        $leave->submission_date = now();
        $leave->leave_status_id = HrLeave::STATUS_PENDING; // Pending

        $totalDays = $this->calculateTotalDays($request->start_date, $request->end_date);
        $employee = Employee::find($request->employee_id);

        if (!$employee || $employee->leaves_open_balance < $totalDays) {
            return redirect()->back()->with('error', "You don't have enough balance");
        }

        if (!$this->checkStaffingLevel($request->start_date, $request->end_date)) {
            return redirect()->back()->with('error', 'Cannot apply. Less than 70% employees would be present during this period.');
        }

        $leave->total_days = $totalDays;

        if ($request->hasFile('leave_attachment')) {
            $file = $request->file('leave_attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $leave->leave_attachment = $filename;
        }

        $leave->save();

        // System Log
        \App\Models\SystemLog::create([
            'related_table' => 'hr_employees_leaves',
            'related_id' => $leave->leave_id,
            'log_date' => now(),
            'log_action' => 'Leave_Request_Added',
            'log_remark' => '---',
            'logger_type' => 'employees_list',
            'logged_by' => Auth::user()->user_id,
            'log_type' => 'int'
        ]);

        // Notify Employee
        \App\Services\NotificationService::send(
            "A leave request has been created for you by HR.",
            "emp/leaves",
            $leave->employee_id
        );

        return redirect()->route('hr.leaves.index')->with('success', 'Leave request created successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $leave = HrLeave::findOrFail($id);
        $oldStatus = $leave->leave_status_id;
        $newStatus = (int) $request->status_id;
        $remark = $request->log_remark ?? '---';

        // Legacy compatibility:
        // 100 in request -> status 2 (Sent for approval)
        // 200 in request -> status 6 (Sent back to user)
        // Others used directly if provided

        if ($newStatus == HrLeave::ACTION_SEND_FOR_APPROVAL) {
            $leave->leave_status_id = HrLeave::STATUS_PENDING_APPROVAL; // Sent for approval
        } else if ($newStatus == HrLeave::ACTION_SEND_BACK) {
            $leave->leave_status_id = HrLeave::STATUS_ACTION_REQUIRED; // Sent back to user
        } else {
            $leave->leave_status_id = $newStatus;
        }

        $leave->save();

        $employee = Employee::find($leave->employee_id);

        // System Log
        \App\Models\SystemLog::create([
            'related_table' => 'hr_employees_leaves',
            'related_id' => $leave->leave_id,
            'log_date' => now(),
            'log_action' => 'Leave_Updated',
            'log_remark' => $remark,
            'logger_type' => 'employees_list',
            'logged_by' => Auth::user()->user_id,
            'log_type' => 'int'
        ]);

        // Specific logic for "Sent for Approval"
        if ($leave->leave_status_id == HrLeave::STATUS_PENDING_APPROVAL) {
            // Get line manager
            $department = $employee->department;
            $lineManagerId = $department ? $department->line_manager_id : 0;

            if ($lineManagerId != 0) {
                // Insert into hr_approvals
                \App\Models\HrApproval::create([
                    'related_table' => 'hr_leaves',
                    'related_id' => $leave->leave_id,
                    'sent_date' => now(),
                    'sent_to_id' => $lineManagerId,
                    'log_remark' => $remark,
                    'added_by' => Auth::user()->user_id
                ]);

                // Notify Line Manager
                \App\Services\NotificationService::send(
                    "You have a pending leave approval request.",
                    "hr_approvals/list/", // Legacy path
                    $lineManagerId
                );
            }
        }

        // Notify employee
        $statusMsg = "Your leave request status has been updated.";
        if ($leave->leave_status_id == HrLeave::STATUS_PENDING_APPROVAL)
            $statusMsg = "Your leave application has been sent for approval.";
        if ($leave->leave_status_id == HrLeave::STATUS_ACTION_REQUIRED)
            $statusMsg = "Your request is pending your action - " . $remark;

        \App\Services\NotificationService::send(
            $statusMsg,
            "emp/leaves",
            $leave->employee_id
        );

        return redirect()->back()->with('success', 'Leave status updated.');
    }

    private function calculateTotalDays($start, $end)
    {
        $startDate = \Carbon\Carbon::parse($start);
        $endDate = \Carbon\Carbon::parse($end);

        $days = 0;
        $current = $startDate->copy();

        while ($current <= $endDate) {
            // Laravel's Carbon dayOfWeek: 0 (Sunday) to 6 (Saturday)
            // Legacy JS: if (day !== 0 && day !== 6) { count++; }
            if ($current->dayOfWeek != 0 && $current->dayOfWeek != 6) {
                $days++;
            }
            $current->addDay();
        }

        return $days;
    }

    private function checkStaffingLevel($startDate, $endDate, $excludeLeaveId = null)
    {
        $totalEmployees = Employee::where('is_deleted', 0)->where('is_hidden', 0)->count();
        if ($totalEmployees == 0)
            return true;

        $activeStatusNames = ['Pending', 'Pending Approval', 'Approved'];
        $activeStatusIds = \Illuminate\Support\Facades\DB::table('hr_employees_leave_status')
            ->whereIn('leave_status_name', $activeStatusNames)
            ->pluck('leave_status_id')
            ->toArray();

        $query = HrLeave::where(function ($q) use ($startDate, $endDate) {
            $q->where('start_date', '<=', $endDate)
                ->where('end_date', '>=', $startDate);
        })->whereIn('leave_status_id', $activeStatusIds);

        if ($excludeLeaveId) {
            $query->where('leave_id', '!=', $excludeLeaveId);
        }

        $employeesOnLeave = $query->distinct('employee_id')->count('employee_id');

        // Add 1 for the person currently applying
        $expectedOnLeave = $employeesOnLeave + 1;
        $expectedPresent = $totalEmployees - $expectedOnLeave;

        return ($expectedPresent / $totalEmployees) >= 0.70;
    }

    public function managerApprove(Request $request, $id)
    {
        $leave = HrLeave::with(['employee', 'type'])->findOrFail($id);
        $employeeId = Auth::user()->employee ? Auth::user()->employee->employee_id : 0;

        // Security: ensure this user is the line manager for this leave
        $approval = \App\Models\HrApproval::where('related_table', 'hr_leaves')
            ->where('related_id', $leave->leave_id)
            ->where('sent_to_id', $employeeId)
            ->first();

        if (!$approval && $leave->line_manager_id != $employeeId) {
            return redirect()->back()->with('error', 'Unauthorized or no pending approval found.');
        }

        // Find GM for forwarding
        $gm   = \App\Models\User::where('is_gm', 1)->where('is_active', 1)->first();
        $gmId = $gm ? $gm->user_id : null;

        // Move to Pending GM
        $leave->leave_status_id = HrLeave::STATUS_PENDING_GM;
        $leave->lm_comments     = $request->input('lm_comments');
        $leave->lm_reviewed_at  = now();
        $leave->gm_id           = $gmId;
        $leave->save();

        // Log
        \App\Models\SystemLog::create([
            'related_table' => 'hr_employees_leaves',
            'related_id'    => $leave->leave_id,
            'log_date'      => now(),
            'log_action'    => 'Manager_Approved',
            'log_remark'    => 'Approved by Line Manager. Forwarded to GM for final decision.',
            'logger_type'   => 'employees_list',
            'logged_by'     => Auth::user()->user_id,
            'log_type'      => 'int'
        ]);

        // Notify GM
        if ($gm) {
            \App\Services\NotificationService::send(
                "Leave #{$leave->leave_id} by {$leave->employee->full_name} approved by Line Manager — awaiting your final decision.",
                "admin/leaves/gm",
                $gm->user_id
            );
        }

        // Notify Employee
        \App\Services\NotificationService::send(
            "Your leave request has been reviewed by your Line Manager and forwarded to the GM for final approval.",
            "emp/leaves",
            $leave->employee_id
        );

        return redirect()->back()->with('success', 'Leave approved and forwarded to GM for final decision.');
    }

    public function managerReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);
        $leave = HrLeave::with(['employee'])->findOrFail($id);
        $employeeId = Auth::user()->employee ? Auth::user()->employee->employee_id : 0;

        $approval = \App\Models\HrApproval::where('related_table', 'hr_leaves')
            ->where('related_id', $leave->leave_id)
            ->where('sent_to_id', $employeeId)
            ->first();

        if (!$approval && $leave->line_manager_id != $employeeId) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $leave->leave_status_id = HrLeave::STATUS_REJECTED;
        $leave->lm_comments     = $request->reason;
        $leave->lm_reviewed_at  = now();
        $leave->save();

        // Log
        \App\Models\SystemLog::create([
            'related_table' => 'hr_employees_leaves',
            'related_id'    => $leave->leave_id,
            'log_date'      => now(),
            'log_action'    => 'Manager_Rejected',
            'log_remark'    => $request->reason,
            'logger_type'   => 'employees_list',
            'logged_by'     => Auth::user()->user_id,
            'log_type'      => 'int'
        ]);

        // Notify Employee
        \App\Services\NotificationService::send(
            "Your leave request #{$leave->leave_id} was rejected by your Line Manager. Reason: {$request->reason}",
            "emp/leaves",
            $leave->employee_id
        );

        return redirect()->back()->with('success', 'Leave request rejected.');
    }
}
