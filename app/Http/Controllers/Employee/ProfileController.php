<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // In this system, users_list.user_id matches employees_list.employee_id
        $employee = Employee::with(['department', 'designation', 'leaves'])->find($user->user_id);
        
        if (!$employee) {
            return redirect()->route('emp.dashboard')->with('error', 'Employee record not found.');
        }

        // Fetch all leave types and calculate balances
        $leaveTypes = \App\Models\LeaveType::all();
        $balances = [];
        $currentYear = now()->year;

        foreach ($leaveTypes as $type) {
            // Skip if limit is 0 and no usage (to keep UI clean, or show all if preferred)
            if ($type->annual_limit == 0) continue;

            $usedDays = $employee->leaves
                ->where('leave_type_id', $type->leave_type_id)
                ->where('leave_status_id', \App\Models\HrLeave::STATUS_APPROVED)
                ->filter(function($leave) use ($currentYear) {
                    return \Carbon\Carbon::parse($leave->start_date)->year == $currentYear;
                })
                ->sum(function($leave) {
                    $start = \Carbon\Carbon::parse($leave->start_date);
                    $end = \Carbon\Carbon::parse($leave->end_date);
                    return $start->diffInDays($end) + 1;
                });

            $balances[] = (object)[
                'name' => $type->leave_type_name,
                'limit' => (float)$type->annual_limit,
                'used' => (float)$usedDays,
                'remaining' => (float)max(0, $type->annual_limit - $usedDays)
            ];
        }

        return view('emp.profile.index', compact('user', 'employee', 'balances'));
    }

    public function updateTheme(Request $request)
    {
        // Legacy 'settings.php' allowed theme updates.
        // We can implement this if we want to support dynamic themes, or just notify user it's fixed in new design.
        // For now, let's just allow password/email updates if needed, or simple "View Profile".
        // The legacy file showed 'Theme Color' selection.
        
        return redirect()->back()->with('info', 'Theme selection is managed globally in this version.');
    }
}
