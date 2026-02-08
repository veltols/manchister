<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TaskPriority;
use App\Models\TaskStatus;
use App\Models\Employee;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $employeeId = optional(Auth::user()->employee)->employee_id ?? 0;

        $tickets = \App\Models\SupportTicket::with(['category', 'priority', 'status', 'addedBy'])
            ->orderBy('ticket_id', 'desc')
            ->paginate(15);

        $categories = \App\Models\TicketCategory::all();
        $priorities = \App\Models\TaskPriority::all();
        $statuses = \App\Models\TaskStatus::all();
        $employees = \App\Models\Employee::where('is_deleted', 0)->orderBy('first_name')->get();

        return view('hr.tickets.index', [
            'tickets' => $tickets,
            'categories' => $categories,
            'priorities' => $priorities,
            'statuses' => $statuses,
            'employees' => $employees
        ]);
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $tickets = \App\Models\SupportTicket::with(['category', 'priority', 'status', 'addedBy'])
            ->orderBy('ticket_id', 'desc')
            ->paginate($perPage);

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

    public function show($id)
    {
        $ticket = \App\Models\SupportTicket::with(['category', 'priority', 'status', 'addedBy'])->findOrFail($id);
        return view('hr.tickets.show', compact('ticket'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ticket_subject' => 'required|string|max:255',
            'ticket_description' => 'required|string',
            'category_id' => 'required|exists:support_tickets_list_cats,category_id',
            'priority_id' => 'required|exists:sys_list_priorities,priority_id',
            'added_by' => 'required|exists:employees_list,employee_id',
        ]);

        $employee = Employee::find($request->added_by);
        $departmentId = $employee ? $employee->department_id : 0;

        $ticket = new \App\Models\SupportTicket();
        $ticket->ticket_ref = 'T-' . time();
        $ticket->ticket_subject = $request->ticket_subject;
        $ticket->ticket_description = $request->ticket_description;
        $ticket->category_id = $request->category_id;
        $ticket->priority_id = $request->priority_id;
        $ticket->added_by = $request->added_by;
        $ticket->department_id = $departmentId;
        $ticket->ticket_added_date = now();
        $ticket->status_id = 1; // Default Pending/Open

        if ($request->hasFile('ticket_attachment')) {
            $file = $request->file('ticket_attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $ticket->ticket_attachment = $fileName;
        }

        $ticket->save();

        $this->logAction($ticket->ticket_id, 'Ticket_Added', 'Initial ticket creation');

        // Send Notifications
        \App\Services\NotificationService::send(
            "A new ticket has been added, REF: " . $ticket->ticket_ref,
            "tickets/list", 
            $ticket->added_by
        );

        // Notify IT Admin
        \App\Services\NotificationService::send(
            "A new ticket has been added, REF: " . $ticket->ticket_ref,
            "tickets/list", 
            1
        );

        return response()->json(['success' => true, 'message' => 'Ticket created successfully']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|integer',
            'ticket_remarks' => 'required|string',
            'assigned_to' => 'nullable|integer'
        ]);

        $ticket = \App\Models\SupportTicket::findOrFail($id);
        $statusId = (int) $request->status_id;
        $logAction = "Ticket_Status_changed";

        if ($statusId == 100) { // Reopen
            $ticket->status_id = 1;
            $logAction = "Ticket_Reopened";
        } else if ($statusId == 2) { // In Progress
            $ticket->status_id = 2;
            $logAction = "Ticket_in_Progress";
            if ($request->assigned_to) {
                $ticket->assigned_to = $request->assigned_to;
                $ticket->assigned_date = now();
                $logAction = "Ticket_Assigned";
            }
        } else if ($statusId == 3) { // Resolved
            $ticket->status_id = 3;
            $ticket->ticket_end_date = now();
            $logAction = "Ticket_Resolved";
        } else {
            $ticket->status_id = $statusId;
        }

        if ($request->ticket_remarks) {
            $ticket->ticket_remarks = $request->ticket_remarks;
        }

        $ticket->save();

        $this->logAction($ticket->ticket_id, $logAction, $request->ticket_remarks);

        // Notifications
        if ($logAction == "Ticket_Assigned" && $ticket->assigned_to != 0) {
            // Notify Assignee
            \App\Services\NotificationService::send(
                "A new ticket assigned to you", 
                "tickets/list/", 
                $ticket->assigned_to
            );

            // Notify Requester
            \App\Services\NotificationService::send(
                "Your ticket has been assigned to IT Agent", 
                "tickets/list/", 
                $ticket->added_by
            );
        }

        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }

    private function logAction($ticketId, $action, $remark)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $log = new SystemLog();
        $log->related_table = 'support_tickets_list';
        $log->related_id = $ticketId;
        $log->log_action = $action;
        $log->log_remark = $remark;
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();
    }
}
