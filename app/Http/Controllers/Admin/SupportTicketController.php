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

        return view('admin.tickets.show', compact('ticket'));
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

        $ticket = new SupportTicket();
        $ticket->ticket_ref = $ref;
        $ticket->category_id = $request->category_id;
        $ticket->priority_id = $request->priority_id;
        $ticket->ticket_subject = $request->ticket_subject;
        $ticket->ticket_description = $request->ticket_description;
        $ticket->ticket_attachment = $attachmentPath;
        $ticket->added_by = $request->added_by;
        $ticket->ticket_added_date = now();
        $ticket->status_id = 1; // Open
        $ticket->assigned_to = 0; // Unassigned initially
        $ticket->save();

        $this->logAction($ticket->ticket_id, 'Ticket Created (Admin)', 'Ticket created by Admin');

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
        $ticket->save();

        $this->logAction($ticket->ticket_id, 'Ticket Assigned', $request->ticket_remarks);

        return redirect()->back()->with('success', 'Ticket assigned successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|integer', // 2=In Progress, 3=Resolved, 1=Reopen (mapped logic)
            'ticket_remarks' => 'required|string'
        ]);

        $ticket = SupportTicket::findOrFail($id);
        
        // Map status logic
        // reOpenTicket sends op=100 in legacy, let's look at legacy logic
        // Legacy: 2=In Progress, 3=Resolved. Reopen logic usually sets back to 1.
        
        $statusId = $request->status_id;
        if($statusId == 100) { // Reopen code from legacy
            $statusId = 1; // Open
        }

        $ticket->status_id = $statusId;
        $ticket->save();

        $actionName = match((int)$statusId) {
            1 => 'Ticket Reopened',
            2 => 'Status: In Progress',
            3 => 'Ticket Resolved',
            default => 'Status Update'
        };

        $this->logAction($ticket->ticket_id, $actionName, $request->ticket_remarks);

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
}
