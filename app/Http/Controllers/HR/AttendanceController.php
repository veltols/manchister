<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('employee')
            ->orderBy('checkin_date', 'desc')
            ->orderBy('checkin_time', 'desc')
            ->paginate(20);

        $employees = Employee::where('is_deleted', 0)->where('is_hidden', 0)->orderBy('first_name')->get();

        return view('hr.attendance.index', compact('attendances', 'employees'));
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $attendances = Attendance::with('employee')
            ->orderBy('checkin_date', 'desc')
            ->orderBy('checkin_time', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $attendances->items(),
            'pagination' => [
                'current_page' => $attendances->currentPage(),
                'last_page' => $attendances->lastPage(),
                'per_page' => $attendances->perPage(),
                'total' => $attendances->total(),
                'from' => $attendances->firstItem(),
                'to' => $attendances->lastItem(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees_list,employee_id',
            'checkin_date' => 'required|date',
            'checkin_time' => 'required',
            'attendance_remarks' => 'nullable|string',
        ]);

        $attendance = new Attendance();
        $attendance->employee_id = $request->employee_id;
        $attendance->checkin_date = $request->checkin_date;
        $attendance->checkin_time = $request->checkin_time;
        $attendance->attendance_remarks = $request->attendance_remarks;
        $attendance->added_date = now();
        $attendance->added_by = \Illuminate\Support\Facades\Auth::id() ?? 0;
        
        $attendance->save();

        return redirect()->back()->with('success', 'Attendance record added successfully.');
    }
}
