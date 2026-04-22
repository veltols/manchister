<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrLeave;
use App\Models\LeaveType;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $user       = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $statusId  = $request->input('status');
        $search    = $request->input('search');
        $typeId    = $request->input('type_id');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $query = HrLeave::with(['type', 'latestLog'])
            ->where('employee_id', $employeeId);

        if ($statusId)  { $query->where('leave_status_id', $statusId); }
        if ($typeId)    { $query->where('leave_type_id', $typeId); }
        if ($startDate) { $query->where('start_date', '>=', $startDate); }
        if ($endDate)   { $query->where('end_date', '<=', $endDate); }

        if ($search) {
            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) $q->where('leave_id', $search);
                $q->orWhere('leave_remarks', 'like', "%{$search}%");
            });
        }

        $leaves   = $query->orderBy('leave_id', 'desc')->paginate(15);
        $leaveTypes = LeaveType::orderBy('leave_type_id')->get();
        $statuses   = \Illuminate\Support\Facades\DB::table('hr_employees_leave_status')->get();

        // --- Per-type balance breakdown ---
        $currentYear    = now()->year;
        $leaveBalances  = [];

        foreach ($leaveTypes as $type) {
            if ((float) $type->annual_limit <= 0) continue;

            $usedDays = HrLeave::where('employee_id', $employeeId)
                ->where('leave_type_id', $type->leave_type_id)
                ->where('leave_status_id', HrLeave::STATUS_APPROVED)
                ->whereYear('start_date', $currentYear)
                ->sum('total_days');

            $leaveBalances[] = [
                'id'        => $type->leave_type_id,
                'name'      => $type->leave_type_name,
                'limit'     => (float) $type->annual_limit,
                'used'      => (float) $usedDays,
                'remaining' => max(0, (float) $type->annual_limit - (float) $usedDays),
            ];
        }

        // --- Summary stats (overall totals) ---
        $totalApprovedDays = HrLeave::where('employee_id', $employeeId)
            ->where('leave_status_id', HrLeave::STATUS_APPROVED)
            ->whereYear('start_date', $currentYear)
            ->sum('total_days');

        $totalPendingDays = HrLeave::where('employee_id', $employeeId)
            ->whereIn('leave_status_id', [HrLeave::STATUS_PENDING, HrLeave::STATUS_PENDING_APPROVAL])
            ->whereYear('start_date', $currentYear)
            ->sum('total_days');

        $leaveStats = [
            'availed'  => $totalApprovedDays,
            'pending'  => $totalPendingDays,
        ];

        return view('emp.leaves.index', compact(
            'leaves', 'leaveTypes', 'statusId', 'statuses', 'leaveStats', 'leaveBalances'
        ));
    }

    /**
     * Leave-type-aware rules:
     *
     * NO BACKDATING + STAFFING CHECK (planned / scheduled leaves):
     *   - Annual Leave       (30 days/yr)
     *   - Special Leave      (10 days/yr)
     *   - Meeting            (180 days/yr)
     *   - Training           (180 days/yr)
     *
     * BACKDATING ALLOWED, STAFFING CHECK SKIPPED (emergency / unavoidable):
     *   - Sick Leave         (10 days/yr)
     *   - Long Sick Leave    (170 days/yr)
     *   - Maternity Leave    (45 days/yr)
     *   - Patient Escort Leave (90 days/yr)
     *   - Compassionate Leave  (15 days/yr)
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id'    => 'required|exists:hr_employees_leave_types,leave_type_id',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'leave_remarks'    => 'required|string',
            'leave_attachment' => 'nullable|file|mimes:pdf,jpg,png,jpeg,csv,doc,docx,xls,xlsx|max:8192',
        ]);

        $user       = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $leaveType  = LeaveType::findOrFail($request->leave_type_id);
        $typeName   = strtolower(trim($leaveType->leave_type_name));

        // -----------------------------------------------------------------------
        // 1. Define behaviour groups by leave type name
        // -----------------------------------------------------------------------

        // Planned leaves: must be applied BEFORE the start date (no backdating)
        $plannedTypes = ['annual leave', 'special leave', 'meeting', 'training'];

        // Emergency / unavoidable leaves: backdating is allowed AND staffing check
        // is skipped (you cannot deny someone sick or bereavement leave based on headcount)
        $emergencyTypes = [
            'sick leave', 'long sick leave', 'maternity leave',
            'patient escort leave', 'compassionate leave',
        ];

        $isPlanned   = in_array($typeName, $plannedTypes);
        $isEmergency = in_array($typeName, $emergencyTypes);

        // -----------------------------------------------------------------------
        // 2. Backdating restriction (planned leaves only)
        // -----------------------------------------------------------------------
        if ($isPlanned) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            if ($startDate->isBefore(now()->startOfDay())) {
                return redirect()->back()->with(
                    'error',
                    "'{$leaveType->leave_type_name}' must be applied in advance. Back-dating is not allowed for this leave type."
                );
            }
        }

        $totalDays   = $this->calculateTotalDays($request->start_date, $request->end_date);
        $currentYear = now()->year;

        // -----------------------------------------------------------------------
        // 3. Per-type annual balance check (all types with a limit set)
        // -----------------------------------------------------------------------
        if ((float) $leaveType->annual_limit > 0) {
            $usedDays = HrLeave::where('employee_id', $employeeId)
                ->where('leave_type_id', $leaveType->leave_type_id)
                ->where('leave_status_id', HrLeave::STATUS_APPROVED)
                ->whereYear('start_date', $currentYear)
                ->sum('total_days');

            $remaining = (float) $leaveType->annual_limit - (float) $usedDays;

            if ($totalDays > $remaining) {
                return redirect()->back()->with(
                    'error',
                    "Insufficient '{$leaveType->leave_type_name}' balance. "
                    . "You have {$remaining} day(s) remaining (used {$usedDays} of {$leaveType->annual_limit} allowed this year)."
                );
            }
        }

        // -----------------------------------------------------------------------
        // 4. 70% staffing check — SKIP for emergency/medical leave types
        // -----------------------------------------------------------------------
        if (!$isEmergency) {
            if (!$this->checkStaffingLevel($request->start_date, $request->end_date)) {
                return redirect()->back()->with(
                    'error',
                    'Cannot apply leave. Less than 70% of employees would be present during this period. Please coordinate with HR.'
                );
            }
        }

        // --- Determine workflow ---
        $department    = $user->employee->department;
        $lineManagerId = $department ? $department->line_manager_id : 0;
        $hasManager    = $lineManagerId && $lineManagerId != $employeeId;

        // --- Create leave record ---
        // gm_id is intentionally NULL here — it will be set when the LM forwards to GM
        $leave = new HrLeave();
        $leave->employee_id      = $employeeId;
        $leave->leave_type_id    = $request->leave_type_id;
        $leave->start_date       = $request->start_date;
        $leave->end_date         = $request->end_date;
        $leave->leave_remarks    = $request->leave_remarks;
        $leave->submission_date  = now();
        $leave->total_days       = $totalDays;
        $leave->line_manager_id  = $hasManager ? $lineManagerId : null;
        $leave->gm_id            = null; // Assigned later when LM forwards to GM
        $leave->leave_status_id  = $hasManager
            ? HrLeave::STATUS_PENDING_APPROVAL
            : HrLeave::STATUS_PENDING;

        if ($request->hasFile('leave_attachment')) {
            $file      = $request->file('leave_attachment');
            $filename  = \Illuminate\Support\Str::random(64) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $leave->leave_attachment = $filename;
        }

        $leave->save();

        // --- System log ---
        \App\Models\SystemLog::create([
            'related_table' => 'hr_employees_leaves',
            'related_id'    => $leave->leave_id,
            'log_date'      => now(),
            'log_action'    => 'Leave_Request_Added',
            'log_remark'    => "Submitted {$leaveType->leave_type_name} ({$totalDays} days). Flow: Employee → " . ($hasManager ? 'Line Manager → GM' : 'HR'),
            'logger_type'   => 'employees_list',
            'logged_by'     => $user->user_id,
            'log_type'      => 'int',
        ]);

        // --- Workflow notifications ---
        if ($hasManager) {
            \App\Models\HrApproval::create([
                'related_table' => 'hr_leaves',
                'related_id'    => $leave->leave_id,
                'sent_date'     => now(),
                'sent_to_id'    => $lineManagerId,
                'log_remark'    => 'Automatically sent to Line Manager for review.',
                'added_by'      => $user->user_id,
            ]);

            \App\Services\NotificationService::send(
                "New {$leaveType->leave_type_name} request from {$user->employee->full_name} — awaiting your review.",
                "emp/lm/leaves",
                $lineManagerId
            );
        } else {
            // No manager — notify HR directly
            $hrUsers = \App\Models\User::where('user_type', 'hr')->where('is_active', 1)->get();
            foreach ($hrUsers as $hr) {
                \App\Services\NotificationService::send(
                    "New {$leaveType->leave_type_name} request from {$user->employee->full_name} (no Line Manager assigned).",
                    "hr/leaves",
                    $hr->user_id
                );
            }
        }

        // Confirm to employee
        \App\Services\NotificationService::send(
            "Your {$leaveType->leave_type_name} request has been submitted and is awaiting Line Manager review.",
            "emp/leaves",
            $employeeId
        );

        return redirect()->back()->with('success', 'Leave request submitted. Awaiting Line Manager review.');
    }

    public function resubmit(Request $request, $id)
    {
        $leave = HrLeave::findOrFail($id);

        $user       = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        if ($leave->employee_id != $employeeId) abort(403);

        $request->validate([
            'leave_type_id'    => 'required|exists:hr_employees_leave_types,leave_type_id',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'leave_remarks'    => 'required|string',
            'leave_attachment' => 'nullable|file|mimes:pdf,jpg,png,jpeg,csv,doc,docx,xls,xlsx|max:8192',
        ]);

        $leaveType = LeaveType::findOrFail($request->leave_type_id);
        $typeName  = strtolower(trim($leaveType->leave_type_name));

        // Same type-behaviour groups as store()
        $plannedTypes   = ['annual leave', 'special leave', 'meeting', 'training'];
        $emergencyTypes = [
            'sick leave', 'long sick leave', 'maternity leave',
            'patient escort leave', 'compassionate leave',
        ];
        $isPlanned   = in_array($typeName, $plannedTypes);
        $isEmergency = in_array($typeName, $emergencyTypes);

        // --- Backdating restriction (planned leaves only) ---
        if ($isPlanned) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            if ($startDate->isBefore(now()->startOfDay())) {
                return redirect()->back()->with(
                    'error',
                    "'{$leaveType->leave_type_name}' must be applied in advance. Back-dating is not allowed for this leave type."
                );
            }
        }

        $totalDays   = $this->calculateTotalDays($request->start_date, $request->end_date);
        $currentYear = now()->year;

        // --- Per-type balance (exclude this leave from used days) ---
        if ((float) $leaveType->annual_limit > 0) {
            $usedDays = HrLeave::where('employee_id', $employeeId)
                ->where('leave_type_id', $leaveType->leave_type_id)
                ->where('leave_status_id', HrLeave::STATUS_APPROVED)
                ->whereYear('start_date', $currentYear)
                ->where('leave_id', '!=', $id)
                ->sum('total_days');

            $remaining = (float) $leaveType->annual_limit - (float) $usedDays;
            if ($totalDays > $remaining) {
                return redirect()->back()->with(
                    'error',
                    "Insufficient '{$leaveType->leave_type_name}' balance. "
                    . "You have {$remaining} day(s) remaining (used {$usedDays} of {$leaveType->annual_limit} allowed this year)."
                );
            }
        }

        // --- Staffing check — skip for emergency/medical types ---
        if (!$isEmergency) {
            if (!$this->checkStaffingLevel($request->start_date, $request->end_date, $id)) {
                return redirect()->back()->with(
                    'error',
                    'Cannot apply leave. Less than 70% of employees would be present during this period. Please coordinate with HR.'
                );
            }
        }

        // --- Determine workflow ---
        $department    = $user->employee->department;
        $lineManagerId = $department ? $department->line_manager_id : 0;
        $hasManager    = $lineManagerId && $lineManagerId != $employeeId;

        $leave->leave_type_id    = $request->leave_type_id;
        $leave->start_date       = $request->start_date;
        $leave->end_date         = $request->end_date;
        $leave->leave_remarks    = $request->leave_remarks;
        $leave->total_days       = $totalDays;
        $leave->line_manager_id  = $hasManager ? $lineManagerId : null;
        $leave->gm_id            = null; // Reset — will be set again when LM forwards to GM
        $leave->lm_comments      = null;
        $leave->gm_comments      = null;
        $leave->lm_reviewed_at   = null;
        $leave->gm_reviewed_at   = null;
        $leave->leave_status_id  = $hasManager
            ? HrLeave::STATUS_PENDING_APPROVAL
            : HrLeave::STATUS_PENDING;

        if ($request->hasFile('leave_attachment')) {
            $file     = $request->file('leave_attachment');
            $filename = \Illuminate\Support\Str::random(64) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $leave->leave_attachment = $filename;
        }

        $leave->save();

        \App\Models\SystemLog::create([
            'related_table' => 'hr_employees_leaves',
            'related_id'    => $leave->leave_id,
            'log_date'      => now(),
            'log_action'    => 'Leave_Resubmitted',
            'log_remark'    => "Resubmitted {$leaveType->leave_type_name} ({$totalDays} days). Flow: Employee → " . ($hasManager ? 'Line Manager → GM' : 'HR'),
            'logger_type'   => 'employees_list',
            'logged_by'     => $user->user_id,
            'log_type'      => 'int',
        ]);

        if ($hasManager) {
            \App\Models\HrApproval::updateOrCreate(
                ['related_table' => 'hr_leaves', 'related_id' => $leave->leave_id],
                [
                    'sent_date'  => now(),
                    'sent_to_id' => $lineManagerId,
                    'log_remark' => 'Resubmitted — sent to Line Manager for review.',
                    'added_by'   => $user->user_id,
                ]
            );

            \App\Services\NotificationService::send(
                "Leave #{$leave->leave_id} resubmitted by {$user->employee->full_name} — awaiting your review.",
                "emp/lm/leaves",
                $lineManagerId
            );
        } else {
            $hrUsers = \App\Models\User::where('user_type', 'hr')->where('is_active', 1)->get();
            foreach ($hrUsers as $hr) {
                \App\Services\NotificationService::send(
                    "Leave #{$leave->leave_id} resubmitted by {$user->employee->full_name} (no LM assigned).",
                    "hr/leaves",
                    $hr->user_id
                );
            }
        }

        return redirect()->back()->with('success', 'Leave request resubmitted. Awaiting Line Manager review.');
    }

    public function getData(Request $request)
    {
        $user       = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $perPage    = $request->input('per_page', 15);

        $query = HrLeave::with(['type', 'latestLog'])
            ->where('employee_id', $employeeId);

        if ($request->filled('status'))   { $query->where('leave_status_id', $request->status); }
        if ($request->filled('type_id'))  { $query->where('leave_type_id', $request->type_id); }
        if ($request->filled('start_date')) { $query->where('start_date', '>=', $request->start_date); }
        if ($request->filled('end_date'))   { $query->where('end_date', '<=', $request->end_date); }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) $q->where('leave_id', $search);
                $q->orWhere('leave_remarks', 'like', "%{$search}%");
            });
        }

        $leaves = $query->orderBy('leave_id', 'desc')->paginate($perPage);

        return response()->json([
            'success'    => true,
            'data'       => $leaves->items(),
            'pagination' => [
                'current_page' => $leaves->currentPage(),
                'last_page'    => $leaves->lastPage(),
                'per_page'     => $leaves->perPage(),
                'total'        => $leaves->total(),
                'from'         => $leaves->firstItem(),
                'to'           => $leaves->lastItem(),
            ],
        ]);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function calculateTotalDays($start, $end)
    {
        $startDate = Carbon::parse($start);
        $endDate   = Carbon::parse($end);
        $days      = 0;
        $current   = $startDate->copy();

        while ($current <= $endDate) {
            if ($current->dayOfWeek !== Carbon::SATURDAY && $current->dayOfWeek !== Carbon::SUNDAY) {
                $days++;
            }
            $current->addDay();
        }

        return $days;
    }

    private function checkStaffingLevel($startDate, $endDate, $excludeLeaveId = null)
    {
        $totalEmployees = \App\Models\Employee::where('is_deleted', 0)->where('is_hidden', 0)->count();
        if ($totalEmployees === 0) return true;

        $activeStatusIds = \Illuminate\Support\Facades\DB::table('hr_employees_leave_status')
            ->whereIn('leave_status_name', ['Pending', 'Pending Approval', 'Approved'])
            ->pluck('leave_status_id')
            ->toArray();

        $query = HrLeave::where(function ($q) use ($startDate, $endDate) {
            $q->where('start_date', '<=', $endDate)
              ->where('end_date', '>=', $startDate);
        })->whereIn('leave_status_id', $activeStatusIds);

        if ($excludeLeaveId) $query->where('leave_id', '!=', $excludeLeaveId);

        $employeesOnLeave = $query->distinct('employee_id')->count('employee_id');
        $expectedOnLeave  = $employeesOnLeave + 1;
        $expectedPresent  = $totalEmployees - $expectedOnLeave;

        return ($expectedPresent / $totalEmployees) >= 0.70;
    }
}
