<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SupportTicket;
use App\Models\SupportTicketCategory;
use App\Models\Priority;
use App\Models\Employee;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $stt = $request->input('stt', 0); // 0=All, 1=Open, 2=In Progress, 3=Resolved, 4=Unassigned

        $query = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'latestLog.logger']);

        // Filter by Status
        if ($stt == 1) {
            $query->where('status_id', 1);
        } elseif ($stt == 2) {
            $query->where('status_id', 2);
        } elseif ($stt == 3) {
            $query->where('status_id', 3);
        } elseif ($stt == 4) {
            // Unassigned (Open and assigned_to = 0)
            $query->where('status_id', 1)
                  ->where('assigned_to', 0);
        }

        // Order by latest
        $tickets = $query->orderBy('ticket_id', 'desc')->paginate(10);

        // Data for "Create Ticket" Modal
        $categories = SupportTicketCategory::all();
        $priorities = Priority::all();

        // Employees in same department
        $myDeptId = $user->employee ? $user->employee->department_id : 0;
        $deptEmployees = Employee::where('department_id', $myDeptId)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->get();

        return view('emp.tickets.index', compact('tickets', 'stt', 'categories', 'priorities', 'deptEmployees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'added_by' => 'required|integer',
            'ticket_subject' => 'required|string|max:255',
            'ticket_description' => 'required|string',
            'category_id' => 'required|integer',
            'priority_id' => 'required|integer',
            'ticket_attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $departmentId = $user->employee ? $user->employee->department_id : 0;

        // Fetch the "Added By" employee's department if necessary
        $addedByEmp = Employee::find($request->added_by);
        if ($addedByEmp) {
            $departmentId = $addedByEmp->department_id;
        }

        // Upload attachment if exists
        $attachmentName = 'no-img.png';
        if ($request->hasFile('ticket_attachment')) {
            $file = $request->file('ticket_attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $attachmentName = $filename;
        }

        // Generate Ticket REF (Legacy style: T-timestamp)
        $ref = 'T-' . time();

        $ticket = new SupportTicket();
        $ticket->ticket_ref = $ref;
        $ticket->ticket_subject = $request->ticket_subject;
        $ticket->ticket_description = $request->ticket_description;
        $ticket->category_id = $request->category_id;
        $ticket->priority_id = $request->priority_id;
        $ticket->ticket_attachment = $attachmentName;

        $ticket->added_by = $request->added_by;
        $ticket->department_id = $departmentId;
        $ticket->ticket_added_date = now();
        $ticket->last_updated_date = now();

        $ticket->status_id = 1; // Project default typically 1 for Open
        $ticket->assigned_to = 0; 
        $ticket->save();

        // Create Initial Log
        $log = new \App\Models\SystemLog();
        $log->related_table = 'support_tickets_list';
        $log->related_id = $ticket->ticket_id;
        $log->log_action = 'Ticket_Added';
        $log->log_remark = 'Initial ticket creation';
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        return redirect()->route('emp.tickets.index')->with('success', 'Ticket created successfully');
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'logs.logger', 'latestLog.logger'])
            ->findOrFail($id);

        $statuses = \App\Models\SupportTicketStatus::all();

        return view('emp.tickets.show', compact('ticket', 'statuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|integer',
            'log_remark' => 'required|string',
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $ticket = SupportTicket::findOrFail($id);
        $ticket->status_id = $request->status_id;
        $ticket->last_updated_date = now();
        $ticket->save();

        // Create Log
        $log = new \App\Models\SystemLog();
        $log->related_table = 'support_tickets_list';
        $log->related_id = $id;
        $log->log_action = 'Status Update'; 
        $log->log_remark = $request->log_remark;
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        return redirect()->back()->with('success', 'Ticket status updated successfully');
    }
}
