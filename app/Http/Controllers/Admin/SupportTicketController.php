<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;
use App\Models\Employee;
use App\Models\SupportTicketCategory;
use App\Models\Priority;
use App\Models\SystemLog;

use Illuminate\Support\Str;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $stt = $request->input('stt', 0); // 0=All, 1=Open, 2=In Progress, 3=Resolved

        $query = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'assignedTo']);

        // Filter by Status
        if ($stt == 1) { // Open
            $query->where('status_id', 1);
        } elseif ($stt == 2) { // In Progress
            $query->where('status_id', 2);
        } elseif ($stt == 3) { // Resolved/Closed
            $query->whereIn('status_id', [3, 4]);
        } elseif ($stt == 4) { // Unassigned
            $query->where('assigned_to', 0)->whereIn('status_id', [1, 2]); // Open or In Progress but unassigned
        }

        $tickets = $query->orderBy('ticket_id', 'desc')->paginate(15);

        // IT Employees for Assignment (Legacy Dept ID 4)
        $itEmployees = Employee::where('department_id', 4)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->orderBy('first_name')
            ->get();

        // Data for "New Ticket" Modal (Admin creates on behalf of others)
        $categories = SupportTicketCategory::all();
        $priorities = Priority::all();
        $allEmployees = Employee::where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->orderBy('first_name')
            ->get();

        return view('admin.tickets.index', compact('tickets', 'stt', 'itEmployees', 'categories', 'priorities', 'allEmployees'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'assignedTo', 'logs.logger'])
            ->findOrFail($id);

        // IT Employees for Assignment (Legacy Dept ID 4)
        $itEmployees = Employee::where('department_id', 4)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->orderBy('first_name')
            ->get();

        $allEmployees = Employee::where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->orderBy('first_name')
            ->get();

        return view('admin.tickets.show', compact('ticket', 'itEmployees', 'allEmployees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'added_by' => 'required|exists:employees_list,employee_id',
            'category_id' => 'required|exists:support_tickets_list_cats,category_id',
            'priority_id' => 'required|exists:sys_list_priorities,priority_id',
            'ticket_subject' => 'required|string|max:255',
            'ticket_description' => 'required|string',
            'ticket_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240'
        ]);

        $attachmentPath = '';
        if ($request->hasFile('ticket_attachment')) {
            $file = $request->file('ticket_attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/tickets'), $filename);
            $attachmentPath = 'uploads/tickets/' . $filename;
        } else {
             $attachmentPath = 'no-img.png';
        }

        // Generate Ticket REF: TR-{RAND}-{YEAR} (Simple unique format)
        $ref = 'TR-' . strtoupper(Str::random(5)) . '-' . date('Y');

        $employee = Employee::find($request->added_by);
        $departmentId = $employee ? $employee->department_id : 0;

        $ticket = new SupportTicket();
        $ticket->ticket_ref = $ref;
        $ticket->category_id = $request->category_id;
        $ticket->priority_id = $request->priority_id;
        $ticket->ticket_subject = $request->ticket_subject;
        $ticket->ticket_description = $request->ticket_description;
        $ticket->ticket_attachment = $attachmentPath;
        $ticket->added_by = $request->added_by;
        $ticket->department_id = $departmentId;
        $ticket->ticket_added_date = now();
        $ticket->status_id = 1; // Open
        $ticket->assigned_to = 0; // Unassigned initially
        $ticket->save();
        
        // Log Action
        $this->logAction($ticket->ticket_id, 'Ticket Created (Admin)', 'Ticket created by Admin');

        // Notification: Notify the employee for whom the ticket was added
        \App\Services\NotificationService::send(
            "A new ticket has been created for you by Admin, REF: " . $ticket->ticket_ref,
            "tickets/list", 
            $ticket->added_by
        );

        return redirect()->back()->with('success', 'Ticket created successfully.');
    }

    public function assign(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:employees_list,employee_id',
            'ticket_remarks' => 'required|string'
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->assigned_to = $request->assigned_to;
        $ticket->assigned_date = now(); // Ensure assigned date is set
        $ticket->save();

        $this->logAction($ticket->ticket_id, 'Ticket Assigned', $request->ticket_remarks);

        // Notify Assignee
        \App\Services\NotificationService::send(
            "A ticket has been assigned to you by Admin, REF: " . $ticket->ticket_ref, 
            "tickets/list/", 
            $ticket->assigned_to
        );

        // Notify Requester
        \App\Services\NotificationService::send(
            "Your ticket has been assigned to an IT Agent, REF: " . $ticket->ticket_ref, 
            "tickets/list/", 
            $ticket->added_by
        );

        return redirect()->back()->with('success', 'Ticket assigned successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|integer', // 2=In Progress, 3=Resolved, 1=Reopen (mapped logic)
            'ticket_remarks' => 'required|string'
        ]);

        $ticket = SupportTicket::findOrFail($id);
        
        $statusId = $request->status_id;
        if($statusId == 100) { // Reopen code from legacy
            $statusId = 1; // Open
        }

        $ticket->status_id = $statusId;

        // Handle Assignment Change if provided
        if ($request->has('assigned_to') && !empty($request->assigned_to)) {
            $ticket->assigned_to = $request->assigned_to;
            $ticket->assigned_date = now();
        }
        
        // Set end date if resolved
        if ($statusId == 3) {
            $ticket->ticket_end_date = now();
        }
        
        $ticket->save();

        $actionName = match((int)$statusId) {
            1 => 'Ticket Reopened',
            2 => 'Status: In Progress',
            3 => 'Ticket Resolved',
            default => 'Status Update'
        };

        $this->logAction($ticket->ticket_id, $actionName, $request->ticket_remarks);

        // Notify Requester
        \App\Services\NotificationService::send(
            "Your ticket status has been updated to " . $ticket->status->status_name . ", REF: " . $ticket->ticket_ref, 
            "tickets/list", 
            $ticket->added_by
        );
        
        // Notify Assignee (if ticket is assigned)
        if ($ticket->assigned_to && $ticket->assigned_to != 0) {
             \App\Services\NotificationService::send(
                "Ticket status updated to " . $ticket->status->status_name . ", REF: " . $ticket->ticket_ref, 
                "tickets/list", 
                $ticket->assigned_to
            );
        }

        return redirect()->back()->with('success', 'Ticket status updated successfully.');
    }

    private function logAction($ticketId, $action, $remark)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0; // Or 0 for root

        $log = new SystemLog();
        $log->related_table = 'support_tickets_list';
        $log->related_id = $ticketId;
        $log->log_action = $action;
        $log->log_remark = $remark;
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list'; // Assuming root is linked or just system
        $log->log_type = 'int';
        $log->save();
    }
    public function getData(Request $request)
    {
        $stt = $request->input('stt', 0);
        $perPage = $request->get('per_page', 15);

        $query = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'assignedTo']);

        if ($stt == 1) {
            $query->where('status_id', 1);
        } elseif ($stt == 2) {
            $query->where('status_id', 2);
        } elseif ($stt == 3) {
            $query->whereIn('status_id', [3, 4]);
        } elseif ($stt == 4) {
             $query->where('assigned_to', 0)->whereIn('status_id', [1, 2]);
        }

        $tickets = $query->orderBy('ticket_id', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $tickets->items(),
            'pagination' => [
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'per_page' => $tickets->perPage(),
                'total' => $tickets->total(),
                'from' => $tickets->firstItem(),
                'to' => $tickets->lastItem(),
            ]
        ]);
    }
}
