<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;
use App\Models\PermissionStatus;
use App\Models\SystemLog;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $statusId = $request->input('status');
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Permission::with('status')
            ->where('employee_id', $employeeId);

        if ($statusId) {
            $query->where('permission_status_id', $statusId);
        }

        if ($search) {
            $query->where('permission_id', 'LIKE', "%$search%");
        }

        if ($startDate) {
            $query->whereDate('start_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('start_date', '<=', $endDate);
        }

        $permissions = $query->orderBy('permission_id', 'desc')
            ->paginate(10);

        $statuses = PermissionStatus::all();

        $isManager = \App\Models\Department::where('line_manager_id', $employeeId)->exists();
        $awaitingApprovals = [];
        if ($isManager) {
            $awaitingApprovals = Permission::with(['employee', 'status'])
                ->where('line_manager_id', $employeeId)
                ->whereIn('permission_status_id', [1, 2])
                ->orderBy('permission_id', 'desc')
                ->get();
        }

        $activeTab = $request->input('tab', 'my-requests');

        return view('emp.permissions.index', compact('permissions', 'statuses', 'isManager', 'awaitingApprovals', 'activeTab'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'permission_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'permission_remarks' => 'required|string',
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $permission = new Permission();
        $permission->submission_date = now();
        $permission->start_date = $request->permission_date;
        $permission->start_time = $request->start_time;
        $permission->end_time = $request->end_time;
        $permission->permission_remarks = $request->permission_remarks;
        $permission->employee_id = $employeeId;
        
        // Find line manager from department
        $lineManagerId = 0;
        if ($user->employee && $user->employee->department_id) {
            $lineManagerId = \App\Models\Department::where('department_id', $user->employee->department_id)
                ->value('line_manager_id') ?? 0;
        }
        $permission->line_manager_id = $lineManagerId;
        
        $permission->permission_status_id = 2; // Pending Approval (instead of 1: Pending)
        $permission->is_exception = $request->has('is_exception') ? 1 : 0;

        // Calculate total hours
        $start = \Carbon\Carbon::parse($request->start_time);
        $end = \Carbon\Carbon::parse($request->end_time);
        $totalHours = ceil(abs($start->diffInMinutes($end, false)) / 60);

        // Check if employee has enough total permission hours remaining
        $employee = clone $user->employee;
        $allowed = $employee->allowed_permission_hours ?? 0;
        $used = $employee->permission_hours_balance ?? 0;

        $remainingHours = max(0, $allowed - $used);

        if ($totalHours > $remainingHours) {
            return redirect()->back()->with('error', "Not enough permission balance available (Allowed: {$allowed}, Used: {$used}, Remaining: {$remainingHours}).");
        }

        // Daily limit check
        $date = \Carbon\Carbon::parse($request->permission_date);
        $dayOfWeek = $date->dayOfWeek; // 0 (Sun) to 6 (Sat)
        $dayName = $date->format('l');
        
        $dayLimit = ($dayOfWeek == \Carbon\Carbon::FRIDAY) ? 1 : 3;
        
        if (!$permission->is_exception && $totalHours > $dayLimit) {
            return redirect()->back()->with('error', "Maximum {$dayLimit} permission hours allowed on {$dayName} (Daily Limit).");
        }

        // Check Monthly limit
        $currentMonth = $date->month;
        $currentYear = $date->year;

        $activeStatusNames = ['Pending', 'Pending Approval', 'Approved'];
        $activeStatusIds = \Illuminate\Support\Facades\DB::table('hr_employees_permissions_status')
            ->whereIn('permission_status_name', $activeStatusNames)
            ->pluck('permission_status_id')
            ->toArray();

        $usedHoursThisMonth = Permission::where('employee_id', $employeeId)
            ->whereMonth('start_date', $currentMonth)
            ->whereYear('start_date', $currentYear)
            ->whereIn('permission_status_id', $activeStatusIds)
            ->sum('total_hours');

        if (!$permission->is_exception && ($usedHoursThisMonth + $totalHours > 8)) {
            return redirect()->back()->with('error', "Maximum 8 permission hours allowed per month. You have already used {$usedHoursThisMonth} hours this month.");
        }

        $permission->total_hours = $totalHours;

        $permission->save();

        // Log entry
        $log = new SystemLog();
        $log->related_table = 'hr_employees_permissions';
        $log->related_id = $permission->permission_id;
        $log->log_action = 'Permission_Requested';
        $log->log_remark = 'Initial permission request';
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        return redirect()->back()->with('success', 'Permission request submitted successfully');
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $perPage = $request->input('per_page', 10);
        
        $statusId = $request->input('status');
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Permission::with('status')
            ->where('employee_id', $employeeId);

        if ($statusId) {
            $query->where('permission_status_id', $statusId);
        }

        if ($search) {
            $query->where('permission_id', 'LIKE', "%$search%");
        }

        if ($startDate) {
            $query->whereDate('start_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('start_date', '<=', $endDate);
        }

        $permissions = $query->orderBy('permission_id', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $permissions->items(),
            'pagination' => [
                'current_page' => $permissions->currentPage(),
                'last_page' => $permissions->lastPage(),
                'per_page' => $permissions->perPage(),
                'total' => $permissions->total(),
                'from' => $permissions->firstItem(),
                'to' => $permissions->lastItem(),
            ]
        ]);
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $permission = Permission::where('permission_id', $id)
            ->where('line_manager_id', $employeeId)
            ->firstOrFail();

        $permission->permission_status_id = 3; // Approved
        $permission->save();

        // Update employee balance
        $employee = $permission->employee;
        if ($employee) {
            $employee->permission_hours_balance += $permission->total_hours;
            $employee->save();
        }

        // Log entry
        $log = new SystemLog();
        $log->related_table = 'hr_employees_permissions';
        $log->related_id = $permission->permission_id;
        $log->log_action = 'Permission_Approved';
        $log->log_remark = 'Approved by line manager';
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        return redirect()->back()->with('success', 'Permission request approved successfully');
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $permission = Permission::where('permission_id', $id)
            ->where('line_manager_id', $employeeId)
            ->firstOrFail();

        $permission->permission_status_id = 4; // Rejected
        $permission->permission_remarks .= "\nRejection Reason: " . $request->input('reason');
        $permission->save();

        // Log entry
        $log = new SystemLog();
        $log->related_table = 'hr_employees_permissions';
        $log->related_id = $permission->permission_id;
        $log->log_action = 'Permission_Rejected';
        $log->log_remark = 'Rejected by line manager: ' . $request->input('reason');
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        return redirect()->back()->with('success', 'Permission request rejected successfully');
    }
}
