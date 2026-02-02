<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 34; // Fallback for test

        $month = $request->input('month', now()->format('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereBetween('checkin_date', [$startDate, $endDate])
            ->orderBy('checkin_date', 'desc')
            ->orderBy('checkin_time', 'desc')
            ->get();

        return view('emp.attendance.index', compact('attendances', 'month'));
    }
}
