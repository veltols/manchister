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
            'leave_attachment' => 'nullable|file|mimes:pdf,jpg,png,jpeg,csv,doc,docx,xls,xlsx|max:8192',
        ]);

        $leave = new HrLeave();
        $leave->employee_id = $request->employee_id;
        $leave->leave_type_id = $request->leave_type_id;
        $leave->start_date = $request->start_date;
        $leave->end_date = $request->end_date;
        $leave->leave_remarks = $request->leave_remarks;
        $leave->submission_date = now();
        $leave->leave_status_id = 1; // Pending
        
        // Calculate total days excluding weekends (Sat/Sun)
        $leave->total_days = $this->calculateTotalDays($request->start_date, $request->end_date);

        if ($request->hasFile('leave_attachment')) {
            $file = $request->file('leave_attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
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
            'logged_by' => Auth::user()->user_id,
            'log_type' => 'int'
        ]);

        // Notify Employee
        \App\Services\NotificationService::send(
            "A leave request has been created for you by HR.", 
            "emp/leaves", 
            $leave->employee_id
        );

        return redirect()->route('hr.leaves.index')->with('success', 'Leave request created successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $leave = HrLeave::findOrFail($id);
        $oldStatus = $leave->leave_status_id;
        $newStatus = (int) $request->status_id;
        $remark = $request->log_remark ?? '---';

        // Legacy compatibility:
        // 100 in request -> status 2 (Sent for approval)
        // 200 in request -> status 6 (Sent back to user)
        // Others used directly if provided
        
        if ($newStatus == 100) {
            $leave->leave_status_id = 2; // Sent for approval
        } else if ($newStatus == 200) {
            $leave->leave_status_id = 6; // Sent back to user
        } else {
            $leave->leave_status_id = $newStatus;
        }

        $leave->save();

        $employee = Employee::find($leave->employee_id);
        
        // System Log
        \App\Models\SystemLog::create([
            'related_table' => 'hr_employees_leaves',
            'related_id' => $leave->leave_id,
            'log_date' => now(),
            'log_action' => 'Leave_Updated',
            'log_remark' => $remark,
            'logger_type' => 'employees_list',
            'logged_by' => Auth::user()->user_id,
            'log_type' => 'int'
        ]);

        // Specific logic for "Sent for Approval"
        if ($leave->leave_status_id == 2) {
            // Get line manager
            $department = $employee->department;
            $lineManagerId = $department ? $department->line_manager_id : 0;

            if ($lineManagerId != 0) {
                // Insert into hr_approvals
                \App\Models\HrApproval::create([
                    'related_table' => 'hr_leaves',
                    'related_id' => $leave->leave_id,
                    'sent_date' => now(),
                    'sent_to_id' => $lineManagerId,
                    'log_remark' => $remark,
                    'added_by' => Auth::user()->user_id
                ]);

                // Notify Line Manager
                \App\Services\NotificationService::send(
                    "You have a pending leave approval request.", 
                    "hr_approvals/list/", // Legacy path
                    $lineManagerId
                );
            }
        }

        // Notify employee
        $statusMsg = "Your leave request status has been updated.";
        if ($leave->leave_status_id == 2) $statusMsg = "Your leave application has been sent for approval.";
        if ($leave->leave_status_id == 6) $statusMsg = "Your request is pending your action - " . $remark;

        \App\Services\NotificationService::send(
            $statusMsg, 
            "emp/leaves", 
            $leave->employee_id
        );

        return redirect()->back()->with('success', 'Leave status updated.');
    }

    private function calculateTotalDays($start, $end)
    {
        $startDate = \Carbon\Carbon::parse($start);
        $endDate = \Carbon\Carbon::parse($end);
        
        $days = 0;
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            // Laravel's Carbon dayOfWeek: 0 (Sunday) to 6 (Saturday)
            // Legacy JS: if (day !== 0 && day !== 6) { count++; }
            if ($current->dayOfWeek != 0 && $current->dayOfWeek != 6) {
                $days++;
            }
            $current->addDay();
        }
        
        return $days;
    }
}
