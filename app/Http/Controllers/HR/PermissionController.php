<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeavePermission;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function index()
    {
        $query = LeavePermission::with(['employee', 'status'])->orderBy('permission_id', 'desc');

        // Emp Filter
        if (Auth::user()->user_type == 'emp') {
            $query->where('employee_id', Auth::user()->employee_id);
        }

        $permissions = $query->paginate(15);

        return view('hr.permissions.index', compact('permissions'));
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
        $perm->employee_id = Auth::user()->employee_id ?? Auth::id();
        $perm->submission_date = now();
        $perm->start_date = $request->start_date;
        $perm->start_time = $request->start_time;
        $perm->end_time = $request->end_time;
        // Calculate total hours
        $start = \Carbon\Carbon::parse($request->start_time);
        $end = \Carbon\Carbon::parse($request->end_time);
        $totalHours = ceil(abs($start->diffInMinutes($end, false)) / 60);

        // Check if employee has enough total permission hours remaining
        $employee = \App\Models\Employee::find($perm->employee_id);
        $allowed = $employee->allowed_permission_hours ?? 0;
        $used = $employee->permission_hours_balance ?? 0;

        $remainingHours = max(0, $allowed - $used);

        if ($totalHours > $remainingHours) {
            return redirect()->back()->with('error', "Not enough permission balance available (Allowed: {$allowed}, Used: {$used}, Remaining: {$remainingHours}).");
        }

        // Check Monthly limit
        $currentMonth = \Carbon\Carbon::parse($request->start_date)->month;
        $currentYear = \Carbon\Carbon::parse($request->start_date)->year;

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

        if ($usedHoursThisMonth + $totalHours > 8) {
            return redirect()->back()->with('error', "Maximum 8 permission hours allowed per month. You have already used {$usedHoursThisMonth} hours this month.");
        }

        $perm->total_hours = $totalHours;
        $perm->permission_remarks = $request->permission_remarks;
        $perm->permission_status_id = 1; // Pending

        $perm->save();

        return redirect()->back()->with('success', 'Permission Request Submitted');
    }
}
