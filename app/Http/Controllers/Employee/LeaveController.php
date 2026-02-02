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
    public function index()
    {
        // Get leaves for the logged-in employee only
        $leaves = HrLeave::with('type')
            ->where('employee_id', Auth::id()) // Assuming LegacyUser ID matches employee_id
            ->orderBy('leave_id', 'desc')
            ->paginate(15);

        $leaveTypes = LeaveType::all();

        return view('emp.leaves.index', compact('leaves', 'leaveTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:hr_employees_leave_types,leave_type_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'leave_remarks' => 'required|string',
        ]);

        $leave = new HrLeave();
        $leave->employee_id = Auth::id();
        $leave->leave_type_id = $request->leave_type_id;
        $leave->start_date = $request->start_date;
        $leave->end_date = $request->end_date;
        $leave->leave_remarks = $request->leave_remarks;
        $leave->submission_date = now();
        $leave->leave_status_id = 0; // Pending

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $leave->total_days = $start->diffInDays($end) + 1;

        $leave->save();

        return redirect()->back()->with('success', 'Leave request submitted successfully.');
    }
}
