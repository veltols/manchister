<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrLeave;
use App\Models\LeaveType;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = HrLeave::with(['employee', 'type'])
            ->orderBy('leave_id', 'desc')
            ->paginate(15);

        $employees = Employee::where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->orderBy('first_name')
            ->get();
            
        $types = LeaveType::all();

        return view('hr.leaves.index', compact('leaves', 'employees', 'types'));
    }

    public function create()
    {
        $employees = Employee::where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->orderBy('first_name')
            ->get();
            
        $types = LeaveType::all();
        
        return view('hr.leaves.create', compact('employees', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees_list,employee_id',
            'leave_type_id' => 'required|exists:hr_employees_leave_types,leave_type_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'leave_remarks' => 'required|string',
        ]);

        $leave = new HrLeave();
        $leave->employee_id = $request->employee_id;
        $leave->leave_type_id = $request->leave_type_id;
        $leave->start_date = $request->start_date;
        $leave->end_date = $request->end_date;
        $leave->leave_remarks = $request->leave_remarks;
        $leave->submission_date = now();
        $leave->leave_status_id = 0; // Default pending
        
        // Calculate total days excluding weekends if needed, simple diff for now
        $start = \Carbon\Carbon::parse($request->start_date);
        $end = \Carbon\Carbon::parse($request->end_date);
        $leave->total_days = $start->diffInDays($end) + 1;

        $leave->save();

        return redirect()->route('hr.leaves.index')->with('success', 'Leave request created successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $leave = HrLeave::findOrFail($id);
        
        $leave->leave_status_id = $request->status_id;
        $leave->save();

        return redirect()->back()->with('success', 'Leave status updated.');
    }
}
