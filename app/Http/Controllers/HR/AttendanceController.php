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

        $employees = Employee::where('is_deleted', 0)->where('is_hidden', 0)->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })->orderBy('first_name')->get();

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
        
        // Calculate Automated Checkout
        $checkinDateTime = \Carbon\Carbon::parse($request->checkin_date . ' ' . $request->checkin_time);
        $checkoutDateTime = $checkinDateTime->copy()->addHours(8);
        $maxTime = \Carbon\Carbon::parse($request->checkin_date . ' 16:00:00');
        
        if ($checkoutDateTime->greaterThan($maxTime)) {
            $checkoutDateTime = $maxTime;
        }
        
        $attendance->checkout_date = $checkoutDateTime->toDateString();
        $attendance->checkout_time = $checkoutDateTime->toTimeString();
        $attendance->total_hours = round($checkinDateTime->diffInMinutes($checkoutDateTime) / 60, 2);
        
        // Calculate status
        $status = 'present';
        if (strtotime($request->checkin_time) > strtotime('08:00:00')) {
            $status = 'late';
        }
        $attendance->attendance_status = $status;
        
        $attendance->attendance_remarks = $request->attendance_remarks;
        $attendance->added_date = now();
        $attendance->added_by = auth()->user()->user_id;
        
        $attendance->save();

        return redirect()->back()->with('success', 'Attendance recorded as ' . ucfirst($status) . '.');
    }

    public function syncAbsents(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        
        // 1. Get all active employees
        $employees = Employee::where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })
            ->get();
            
        $markedCount = 0;
        
        foreach ($employees as $employee) {
            // Check if they already have an attendance record for this date
            $exists = Attendance::where('employee_id', $employee->employee_id)
                ->where('checkin_date', $date)
                ->exists();
                
            if (!$exists) {
                // Determine if they are on leave
                $onLeave = \App\Models\HrLeave::where('employee_id', $employee->employee_id)
                    ->where('leave_status_id', \App\Models\HrLeave::STATUS_APPROVED)
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->exists();
                    
                $status = $onLeave ? 'on leave' : 'absent';
                
                Attendance::create([
                    'employee_id' => $employee->employee_id,
                    'checkin_date' => $date,
                    'attendance_status' => $status,
                    'added_date' => now(),
                    'added_by' => auth()->user()->user_id,
                    'attendance_remarks' => $onLeave ? 'Auto-marked: On Approved Leave' : 'Auto-marked: Absent (No Login)'
                ]);
                
                $markedCount++;
            }
        }
        
        return redirect()->back()->with('success', "Daily attendance finalized. Marked $markedCount employees as absent/on leave.");
    }
}
