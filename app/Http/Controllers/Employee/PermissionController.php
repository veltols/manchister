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
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $permissions = Permission::with('status')
            ->where('employee_id', $employeeId)
            ->orderBy('permission_id', 'desc')
            ->paginate(10);

        $statuses = PermissionStatus::all();

        return view('emp.permissions.index', compact('permissions', 'statuses'));
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
        $permission->permission_status_id = 1; // Pending

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

        // Check Monthly limit
        $currentMonth = \Carbon\Carbon::parse($request->permission_date)->month;
        $currentYear = \Carbon\Carbon::parse($request->permission_date)->year;

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

        if ($usedHoursThisMonth + $totalHours > 8) {
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

        $permissions = Permission::with('status')
            ->where('employee_id', $employeeId)
            ->orderBy('permission_id', 'desc')
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
}
