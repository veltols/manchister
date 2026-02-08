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
    public function index(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $statusId = $request->input('status');

        // Get leaves for the logged-in employee only
        $query = HrLeave::with(['type', 'latestLog'])
            ->where('employee_id', $employeeId);

        if ($statusId) {
            $query->where('leave_status_id', $statusId);
        }

        $leaves = $query->orderBy('leave_id', 'desc')
            ->paginate(15);

        $leaveTypes = LeaveType::all();

        return view('emp.leaves.index', compact('leaves', 'leaveTypes', 'statusId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:hr_employees_leave_types,leave_type_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'leave_remarks' => 'required|string',
            'leave_attachment' => 'nullable|file|mimes:pdf,jpg,png,jpeg,csv,doc,docx,xls,xlsx|max:8192',
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $leave = new HrLeave();
        $leave->employee_id = $employeeId;
        $leave->leave_type_id = $request->leave_type_id;
        $leave->start_date = $request->start_date;
        $leave->end_date = $request->end_date;
        $leave->leave_remarks = $request->leave_remarks;
        $leave->submission_date = now();
        $leave->leave_status_id = 1; // Pending HR

        // Calculate total days excluding weekends (Sat/Sun)
        $leave->total_days = $this->calculateTotalDays($request->start_date, $request->end_date);

        if ($request->hasFile('leave_attachment')) {
            $file = $request->file('leave_attachment');
            $extension = $file->getClientOriginalExtension();
            $filename = \Illuminate\Support\Str::random(64) . '.' . $extension;
            $file->move(public_path('uploads'), $filename);
            $leave->leave_attachment = $filename;
        }

        $leave->save();

        // System Log
        \App\Models\SystemLog::create([
            'related_table' => 'hr_employees_leaves',
            'related_id' => $leave->leave_id,
            'log_date' => now(),
            'log_action' => 'Leave_Request_Added',
            'log_remark' => '---',
            'logger_type' => 'employees_list',
            'logged_by' => $user->user_id,
            'log_type' => 'int'
        ]);

        // Send Notification to Employee
        \App\Services\NotificationService::send(
            "Your leave request has been submitted successfully.", 
            "emp/leaves", 
            $employeeId
        );

        // Notify HR (Always ID 1 in legacy/current system logic for alerts)
        \App\Services\NotificationService::send(
            "New leave request submitted by " . $user->employee->full_name, 
            "hr/leaves", 
            1
        );

        return redirect()->back()->with('success', 'Leave request submitted successfully.');
    }

    public function resubmit(Request $request, $id)
    {
        $leave = HrLeave::findOrFail($id);
        
        // Security check: ensure the user owns this leave
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        if ($leave->employee_id != $employeeId) {
            abort(403);
        }

        $request->validate([
            'leave_type_id' => 'required|exists:hr_employees_leave_types,leave_type_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'leave_remarks' => 'required|string',
            'leave_attachment' => 'nullable|file|mimes:pdf,jpg,png,jpeg,csv,doc,docx,xls,xlsx|max:8192',
        ]);

        $leave->leave_type_id = $request->leave_type_id;
        $leave->start_date = $request->start_date;
        $leave->end_date = $request->end_date;
        $leave->leave_remarks = $request->leave_remarks;
        $leave->leave_status_id = 1; // Return to Pending HR

        // Recalculate duration
        $leave->total_days = $this->calculateTotalDays($request->start_date, $request->end_date);

        if ($request->hasFile('leave_attachment')) {
            $file = $request->file('leave_attachment');
            $extension = $file->getClientOriginalExtension();
            $filename = \Illuminate\Support\Str::random(64) . '.' . $extension;
            $file->move(public_path('uploads'), $filename);
            $leave->leave_attachment = $filename;
        }

        $leave->save();

        // System Log
        \App\Models\SystemLog::create([
            'related_table' => 'hr_employees_leaves',
            'related_id' => $leave->leave_id,
            'log_date' => now(),
            'log_action' => 'Leave_Resubmitted',
            'log_remark' => 'User updated and resubmitted the request.',
            'logger_type' => 'employees_list',
            'logged_by' => $user->user_id,
            'log_type' => 'int'
        ]);

        // Notify HR
        \App\Services\NotificationService::send(
            "Leave request #{$leave->leave_id} has been resubmitted by " . $user->employee->full_name, 
            "hr/leaves", 
            1
        );

        return redirect()->back()->with('success', 'Leave request resubmitted successfully.');
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $statusId = $request->input('status');
        $perPage = $request->input('per_page', 15);

        $query = HrLeave::with(['type', 'latestLog'])
            ->where('employee_id', $employeeId);

        if ($statusId) {
            $query->where('leave_status_id', $statusId);
        }

        $leaves = $query->orderBy('leave_id', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $leaves->items(),
            'pagination' => [
                'current_page' => $leaves->currentPage(),
                'last_page' => $leaves->lastPage(),
                'per_page' => $leaves->perPage(),
                'total' => $leaves->total(),
                'from' => $leaves->firstItem(),
                'to' => $leaves->lastItem(),
            ]
        ]);
    }

    private function calculateTotalDays($start, $end)
    {
        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);
        
        $days = 0;
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            if ($current->dayOfWeek != Carbon::SATURDAY && $current->dayOfWeek != Carbon::SUNDAY) {
                $days++;
            }
            $current->addDay();
        }
        
        return $days;
    }
}
