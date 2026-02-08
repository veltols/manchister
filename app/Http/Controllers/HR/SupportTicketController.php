<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportTicketCategory;
use App\Models\Priority;
use App\Models\SupportTicketStatus;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
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

        $tickets = $query->orderBy('ticket_id', 'desc')->paginate(10);

        // Data for "Create Ticket" Modal
        $categories = SupportTicketCategory::all();
        $priorities = Priority::all();
        $employees = \App\Models\Employee::where('is_deleted', 0)->where('is_hidden', 0)->orderBy('first_name')->get();

        return view('hr.tickets.index', compact('tickets', 'stt', 'categories', 'priorities', 'employees'));
    }

    public function create()
    {
        $categories = SupportTicketCategory::all();
        $priorities = Priority::all();
        return view('hr.tickets.create', compact('categories', 'priorities'));
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
        
        // Fetch Department of Added By User
        $addedByEmp = \App\Models\Employee::find($request->added_by);
        $ticket->department_id = $addedByEmp ? $addedByEmp->department_id : 0;
        
        $ticket->ticket_added_date = now();
        $ticket->status_id = 1; // Open
        $ticket->assigned_to = 0; 
        $ticket->save();

        // Create Initial Log
        $log = new \App\Models\SystemLog();
        $log->related_table = 'support_tickets_list';
        $log->related_id = $ticket->ticket_id;
        $log->log_action = 'Ticket Created'; 
        $log->log_remark = 'Ticket created by HR for employee.';
        $log->log_date = now();
        $log->logged_by = Auth::user()->employee->employee_id ?? 0;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        // Send Notifications
        // 1. Notify the employee who the ticket was added for
        \App\Services\NotificationService::send(
            "A new ticket has been created for you by HR, REF: " . $ticket->ticket_ref,
            "tickets/list", 
            $ticket->added_by
        );

        // 2. Notify IT Admin (System Admin)
        \App\Services\NotificationService::send(
            "A new ticket has been added by HR, REF: " . $ticket->ticket_ref,
            "tickets/list", 
            1
        );

        return redirect()->route('hr.tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'logs.logger', 'latestLog.logger'])
            ->findOrFail($id);
            
        $statuses = \App\Models\SupportTicketStatus::all();
            
        return view('hr.tickets.show', compact('ticket', 'statuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|integer',
            'ticket_remarks' => 'required|string',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $oldStatus = $ticket->status_id;
        $ticket->status_id = $request->status_id;
        
        $logAction = "Status Updated";
        if ($ticket->status_id == 2 && $oldStatus != 2) $logAction = "Ticket In Progress";
        if ($ticket->status_id == 3 && $oldStatus != 3) {
            $logAction = "Ticket Resolved";
            $ticket->ticket_end_date = now(); 
        }

        if($request->has('assigned_to') && $request->assigned_to != "" && $request->assigned_to != $ticket->assigned_to) {
            $ticket->assigned_to = $request->assigned_to;
            $ticket->assigned_date = now();
            $logAction = "Ticket Assigned";
        }

        $ticket->save();

        // Create Log
        $log = new \App\Models\SystemLog();
        $log->related_table = 'support_tickets_list';
        $log->related_id = $id;
        $log->log_action = $logAction; 
        $log->log_remark = $request->ticket_remarks;
        $log->log_date = now();
        $log->logged_by = Auth::user()->employee->employee_id ?? 0;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        // Notifications
        // Notify Requester
        \App\Services\NotificationService::send(
            "Your ticket status has been updated to " . $ticket->status->status_name . ", REF: " . $ticket->ticket_ref, 
            "tickets/list", 
            $ticket->added_by
        );

        // Notify Assignee if assigned
        if ($ticket->assigned_to && $ticket->assigned_to != 0) {
             \App\Services\NotificationService::send(
                "A ticket has been assigned to you, REF: " . $ticket->ticket_ref, 
                "tickets/list", 
                $ticket->assigned_to
            );
        }

        return redirect()->back()->with('success', 'Ticket status updated successfully');
    }
    public function getData(Request $request)
    {
        $stt = $request->input('stt', 0);
        $perPage = $request->get('per_page', 10);

        $query = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'latestLog.logger']);

        if ($stt == 1) {
            $query->where('status_id', 1);
        } elseif ($stt == 2) {
            $query->where('status_id', 2);
        } elseif ($stt == 3) {
            $query->where('status_id', 3);
        } elseif ($stt == 4) {
             $query->where('status_id', 1)->where('assigned_to', 0);
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
