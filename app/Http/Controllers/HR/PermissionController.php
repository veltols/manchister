<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeavePermission;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $search = $request->input('search'); // Ref No
        $statusId = $request->input('status_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = LeavePermission::with(['employee', 'status'])->orderBy('permission_id', 'desc');

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        if ($search) {
            $query->where('permission_id', 'LIKE', "%$search%");
        }
        if ($statusId) {
            $query->where('permission_status_id', $statusId);
        }
        if ($startDate) {
            $query->whereDate('start_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('start_date', '<=', $endDate);
        }

        $permissions = $query->paginate(15);

        $employees = \App\Models\Employee::where('is_deleted', 0)->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })->orderBy('first_name')->get();
        
        $statuses = \Illuminate\Support\Facades\DB::table('hr_employees_permissions_status')->get();

        return view('hr.permissions.index', compact('permissions', 'employees', 'statuses'));
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $employeeId = $request->input('employee_id');
        $search = $request->input('search');
        $statusId = $request->input('status_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = LeavePermission::with(['employee', 'status'])->orderBy('permission_id', 'desc');

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        if ($search) {
            $query->where('permission_id', 'LIKE', "%$search%");
        }
        if ($statusId) {
            $query->where('permission_status_id', $statusId);
        }
        if ($startDate) {
            $query->whereDate('start_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('start_date', '<=', $endDate);
        }

        $permissions = $query->paginate($perPage);

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

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'permission_remarks' => 'required|string',
        ]);

        $perm = new LeavePermission();
        $perm->employee_id = $request->employee_id;
        
        // Get line manager of requested user
        $employee = \App\Models\Employee::find($request->employee_id);
        $lineManagerId = 0;
        if ($employee && $employee->department_id) {
            $lineManagerId = \App\Models\Department::where('department_id', $employee->department_id)
                ->value('line_manager_id') ?? 0;
        }
        $perm->line_manager_id = $lineManagerId;
        $perm->submission_date = now();
        $perm->start_date = $request->start_date;
        $perm->start_time = $request->start_time;
        $perm->end_time = $request->end_time;
        // Calculate total hours
        $start = \Carbon\Carbon::parse($request->start_time);
        $end = \Carbon\Carbon::parse($request->end_time);
        $totalHours = ceil(abs($start->diffInMinutes($end, false)) / 60);

        // Check if employee has enough total permission hours remaining
        $allowed = $employee->allowed_permission_hours ?? 0;
        $used = $employee->permission_hours_balance ?? 0;

        $remainingHours = max(0, $allowed - $used);

        if ($totalHours > $remainingHours) {
            return redirect()->back()->with('error', "Not enough permission balance available (Allowed: {$allowed}, Used: {$used}, Remaining: {$remainingHours}).");
        }

        // Daily limit check
        $date = \Carbon\Carbon::parse($request->start_date);
        $dayOfWeek = $date->dayOfWeek; // 0 (Sun) to 6 (Sat)
        $dayName = $date->format('l');
        
        $dayLimit = ($dayOfWeek == \Carbon\Carbon::FRIDAY) ? 1 : 3;
        
        if (!$request->has('is_exception') && $totalHours > $dayLimit) {
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

        $usedHoursThisMonth = LeavePermission::where('employee_id', $perm->employee_id)
            ->whereMonth('start_date', $currentMonth)
            ->whereYear('start_date', $currentYear)
            ->whereIn('permission_status_id', $activeStatusIds)
            ->sum('total_hours');

        if (!$request->has('is_exception') && ($usedHoursThisMonth + $totalHours > 8)) {
            return redirect()->back()->with('error', "Maximum 8 permission hours allowed per month. You have already used {$usedHoursThisMonth} hours this month.");
        }
        
        $perm->is_exception = $request->has('is_exception') ? 1 : 0;

        $perm->total_hours = $totalHours;
        $perm->permission_remarks = $request->permission_remarks;
        $perm->permission_status_id = 1; // Pending

        $perm->save();

        return redirect()->back()->with('success', 'Permission Request Submitted');
    }
}
