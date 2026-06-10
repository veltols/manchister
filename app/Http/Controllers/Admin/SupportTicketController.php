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
        $stt = $request->input('stt', 0); // 0=All, 1=Open, 2=In Progress, 3=Resolved, 4=Cancelled
        $search = $request->input('search', '');

        // Monthly Resolved Stats (Current Year - January to December)
        $resolvedMonths = [];
        if ($stt == \App\Models\SupportTicketStatus::RESOLVED) {
            $currentYear = \Carbon\Carbon::now()->year;
            $counts = SupportTicket::select(
                \Illuminate\Support\Facades\DB::raw("DATE_FORMAT(ticket_added_date, '%Y-%m') as month_value"),
                \Illuminate\Support\Facades\DB::raw('count(*) as total')
            )
            ->where('status_id', \App\Models\SupportTicketStatus::RESOLVED)
            ->whereYear('ticket_added_date', $currentYear)
            ->groupBy('month_value')
            ->get()
            ->pluck('total', 'month_value');

            for ($m = 1; $m <= 12; $m++) {
                $date = \Carbon\Carbon::createFromDate($currentYear, $m, 1);
                $monthValue = $date->format('Y-m');
                $monthLabel = $date->format('F Y');
                
                $resolvedMonths[] = (object)[
                    'month_value' => $monthValue,
                    'month_label' => $monthLabel,
                    'total' => $counts[$monthValue] ?? 0
                ];
            }
        }

        $query = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'assignedTo']);

        // Filter by Status
        if ($stt == \App\Models\SupportTicketStatus::OPEN) { // Open
            $query->where('status_id', \App\Models\SupportTicketStatus::OPEN);
        } elseif ($stt == \App\Models\SupportTicketStatus::IN_PROGRESS) { // In Progress
            $query->where('status_id', \App\Models\SupportTicketStatus::IN_PROGRESS);
        } elseif ($stt == \App\Models\SupportTicketStatus::RESOLVED) { // Resolved/Closed
            $query->where('status_id', \App\Models\SupportTicketStatus::RESOLVED);
            
            // Filter by Month if selected
            if ($request->filled('month')) {
                $query->where(\Illuminate\Support\Facades\DB::raw("DATE_FORMAT(ticket_added_date, '%Y-%m')"), $request->month);
            }
        } elseif ($stt == \App\Models\SupportTicketStatus::CANCELLED) { // Cancelled
            $query->where('status_id', \App\Models\SupportTicketStatus::CANCELLED);
        }

        if ($request->filled('search')) {
            $query->where('ticket_ref', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('priority_id')) {
            $query->where('priority_id', $request->priority_id);
        }

        $tickets = $query->orderBy('ticket_id', 'desc')->paginate(15);

        // IT Employees for Assignment (Legacy Dept ID 4)
        $itEmployees = Employee::where('department_id', 4)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('first_name')
            ->get();

        // Data for "New Ticket" Modal (Admin creates on behalf of others)
        $categories = SupportTicketCategory::all();
        $priorities = Priority::all();
        $allEmployees = Employee::where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('first_name')
            ->get();

        $groupedEmployees = \App\Models\Department::with(['employees' => function($q) {
            $q->where('is_deleted', 0)
              ->where('is_hidden', 0)
              ->whereHas('systemUser', function($sq) {
                  $sq->where('is_active', 1);
              })
              ->orderBy('first_name');
        }])->get();

        return view('admin.tickets.index', compact('tickets', 'stt', 'itEmployees', 'categories', 'priorities', 'allEmployees', 'resolvedMonths', 'groupedEmployees'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'assignedTo', 'logs.logger'])
            ->findOrFail($id);

        // IT Employees for Assignment (Legacy Dept ID 4)
        $itEmployees = Employee::where('department_id', 4)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('first_name')
            ->get();

        $allEmployees = Employee::where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('first_name')
            ->get();

        $priorities = \App\Models\Priority::all();
        $categories = \App\Models\SupportTicketCategory::all();

        $groupedEmployees = \App\Models\Department::with(['employees' => function($q) {
            $q->where('is_deleted', 0)
              ->where('is_hidden', 0)
              ->whereHas('systemUser', function($sq) {
                  $sq->where('is_active', 1);
              })
              ->orderBy('first_name');
        }])->get();

        return view('admin.tickets.show', compact('ticket', 'itEmployees', 'allEmployees', 'priorities', 'categories', 'groupedEmployees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'added_by'           => 'required|exists:employees_list,employee_id',
            'assigned_to'        => 'required|exists:employees_list,employee_id',
            'category_id'        => 'required|exists:support_tickets_list_cats,category_id',
            'priority_id'        => 'required|exists:sys_list_priorities,priority_id',
            'ticket_subject'     => 'required|string|max:255',
            'ticket_description' => 'required|string',
            'ticket_attachment'  => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:8192'
        ]);

        $attachmentPath = '';
        if ($request->hasFile('ticket_attachment')) {
            $file = $request->file('ticket_attachment');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::random(64) . '.' . $extension;
            $uploadDir = public_path('uploads/tickets');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $filename);
            $attachmentPath = 'uploads/tickets/' . $filename;
        } else {
             $attachmentPath = 'no-img.png';
        }

        // Generate Ticket REF: TK-YYMM001 style
        $ref = SupportTicket::generateReference();

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
        $ticket->status_id = \App\Models\SupportTicketStatus::OPEN; // Open
        // Assign immediately if provided in form
        $ticket->assigned_to = ($request->filled('assigned_to')) ? (int) $request->assigned_to : 0;
        if ($ticket->assigned_to) {
            $ticket->assigned_date = now();
        }
        $ticket->save();
        
        // Log Action
        $this->logAction($ticket->ticket_id, 'Ticket Created (Admin)', 'Ticket created by Admin');

        // Notification: Notify the employee for whom the ticket was added
        \App\Services\NotificationService::send(
            "A new ticket has been created for you by Admin, REF: " . $ticket->ticket_ref,
            "tickets", 
            $ticket->added_by
        );

        return redirect()->back()->with('success', 'Ticket created successfully.');
    }

    public function updateDetails(Request $request, $id)
    {
        $request->validate([
            'ticket_subject' => 'required|string|max:255',
            'priority_id'    => 'required|exists:sys_list_priorities,priority_id',
            'added_by'       => 'required|exists:employees_list,employee_id',
            'status_id'      => 'required|integer',
            'ticket_remarks' => 'nullable|string',
            'category_id'    => 'required|exists:support_tickets_list_cats,category_id',
            'ticket_description' => 'required|string',
            'ticket_attachment'  => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:8192',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        $currentStatusId = (int)$ticket->status_id;
        $newStatusId = (int)$request->status_id;
        $statusResolved = \App\Models\SupportTicketStatus::RESOLVED;

        $ticket->ticket_subject = $request->ticket_subject;
        $ticket->priority_id    = $request->priority_id;
        $ticket->added_by       = $request->added_by;
        $ticket->category_id    = $request->category_id;
        $ticket->ticket_description = $request->ticket_description;

        if ($request->hasFile('ticket_attachment')) {
            $file = $request->file('ticket_attachment');
            $extension = $file->getClientOriginalExtension();
            $filename = \Illuminate\Support\Str::random(64) . '.' . $extension;
            $uploadDir = public_path('uploads/tickets');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $filename);
            $ticket->ticket_attachment = 'uploads/tickets/' . $filename;
        }

        $employee = Employee::find($request->added_by);
        if ($employee) {
            $ticket->department_id = $employee->department_id;
        }

        $ticket->status_id = $newStatusId;
        
        $logAction = 'Ticket Updated';
        if ($request->has('assigned_to') && $request->assigned_to != "" && $request->assigned_to != $ticket->assigned_to) {
            $ticket->assigned_to = $request->assigned_to;
            $ticket->assigned_date = now();
            $logAction = 'Ticket Assigned & Updated';
        }
        
        if ($currentStatusId != $newStatusId && $newStatusId == $statusResolved) {
            $ticket->ticket_end_date = now();
        }

        $ticket->save();

        $this->logAction($ticket->ticket_id, $logAction, $request->ticket_remarks ?? 'Ticket details updated by Admin.');

        return redirect()->back()->with('success', 'Ticket updated successfully.');
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
            "tickets", 
            $ticket->assigned_to
        );

        // Notify Requester
        \App\Services\NotificationService::send(
            "Your ticket has been assigned to an IT Agent, REF: " . $ticket->ticket_ref, 
            "tickets", 
            $ticket->added_by
        );

        return redirect()->back()->with('success', 'Ticket assigned successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|integer',
            'ticket_remarks' => 'required|string'
        ]);

        $ticket = SupportTicket::findOrFail($id);
        
        $currentStatusId = (int)$ticket->status_id;
        $newStatusId = (int)$request->status_id;

        // Legacy Reopen logic handling
        if($newStatusId == 100) { 
            $newStatusId = \App\Models\SupportTicketStatus::OPEN; 
        }

        // Status IDs
        $statusOpen = \App\Models\SupportTicketStatus::OPEN;
        $statusInProgress = \App\Models\SupportTicketStatus::IN_PROGRESS;
        $statusResolved = \App\Models\SupportTicketStatus::RESOLVED;
        $statusCancelled = \App\Models\SupportTicketStatus::CANCELLED;

        // Validation Rules
        if ($currentStatusId == $statusOpen) {
            if (!in_array($newStatusId, [$statusInProgress, $statusCancelled])) {
                return redirect()->back()->with('error', 'From Open, you can only move to In Progress or Cancelled.');
            }
        } elseif ($currentStatusId == $statusInProgress) {
            if (!in_array($newStatusId, [$statusResolved, $statusCancelled])) {
                return redirect()->back()->with('error', 'From In Progress, you can only move to Resolved or Cancelled.');
            }
        } elseif ($currentStatusId == $statusResolved) {
            if ($newStatusId != $statusOpen) {
                return redirect()->back()->with('error', 'Resolved tickets can only be Reopened.');
            }
        } elseif ($currentStatusId == $statusCancelled) {
            if ($newStatusId != $statusOpen) {
                return redirect()->back()->with('error', 'Cancelled tickets can only be Reopened.');
            }
        }

        $ticket->status_id = $newStatusId;

        // Handle Assignment Change if provided
        if ($request->has('assigned_to') && !empty($request->assigned_to)) {
            $ticket->assigned_to = $request->assigned_to;
            $ticket->assigned_date = now();
        }
        
        // Set end date if resolved
        if ($newStatusId == $statusResolved) {
            $ticket->ticket_end_date = now();
        }
        
        $ticket->save();

        $actionName = match((int)$newStatusId) {
            \App\Models\SupportTicketStatus::OPEN => 'Ticket Reopened',
            \App\Models\SupportTicketStatus::IN_PROGRESS => 'Status: In Progress',
            \App\Models\SupportTicketStatus::RESOLVED => 'Ticket Resolved',
            \App\Models\SupportTicketStatus::CANCELLED => 'Ticket Cancelled',
            default => 'Status Update'
        };

        $this->logAction($ticket->ticket_id, $actionName, $request->ticket_remarks);

        // Notify Requester
        \App\Services\NotificationService::send(
            "Your ticket status has been updated to " . ($ticket->status ? $ticket->status->status_name : 'Updated') . ", REF: " . $ticket->ticket_ref, 
            "tickets", 
            $ticket->added_by
        );
        
        // Notify Assignee (if ticket is assigned)
        if ($ticket->assigned_to && $ticket->assigned_to != 0) {
             \App\Services\NotificationService::send(
                "Ticket status updated to " . ($ticket->status ? $ticket->status->status_name : 'Updated') . ", REF: " . $ticket->ticket_ref, 
                "tickets", 
                $ticket->assigned_to
            );
             \App\Services\NotificationService::send(
            "Your ticket has been assigned to an IT Agent, REF: " . $ticket->ticket_ref, 
            "tickets", 
            $ticket->added_by
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
        $log->logged_by = Auth::user()->user_id ?? 1;
        $log->logger_type = 'employees_list'; // Assuming root is linked or just system
        $log->log_type = 'int';
        $log->save();
    }
    public function getData(Request $request)
    {
        $stt = $request->input('stt', 0);
        $perPage = $request->get('per_page', 15);

        $query = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'assignedTo']);

        if ($request->has('search') && $request->search != '') {
            $query->where('ticket_ref', 'LIKE', '%' . $request->search . '%');
        }

        if ($stt == \App\Models\SupportTicketStatus::OPEN) {
            $query->where('status_id', \App\Models\SupportTicketStatus::OPEN);
        } elseif ($stt == \App\Models\SupportTicketStatus::IN_PROGRESS) {
            $query->where('status_id', \App\Models\SupportTicketStatus::IN_PROGRESS);
        } elseif ($stt == \App\Models\SupportTicketStatus::RESOLVED) {
            $query->whereIn('status_id', [\App\Models\SupportTicketStatus::RESOLVED, \App\Models\SupportTicketStatus::CANCELLED]);
            if ($request->filled('month')) {
                $query->where(\Illuminate\Support\Facades\DB::raw("DATE_FORMAT(ticket_added_date, '%Y-%m')"), $request->month);
            }
        } elseif ($stt == \App\Models\SupportTicketStatus::CANCELLED) {
            $query->where('status_id', \App\Models\SupportTicketStatus::CANCELLED);
        } elseif ($stt == 4) {
             $query->where('assigned_to', 0)->whereIn('status_id', [\App\Models\SupportTicketStatus::OPEN, \App\Models\SupportTicketStatus::IN_PROGRESS]);
        }

        if ($request->filled('search')) {
            $query->where('ticket_ref', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('priority_id')) {
            $query->where('priority_id', $request->priority_id);
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
