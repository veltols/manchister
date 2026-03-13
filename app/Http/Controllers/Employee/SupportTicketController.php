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

        // Monthly Resolved Stats
        $resolvedMonths = [];
        if ($stt == \App\Models\SupportTicketStatus::RESOLVED) {
            $resolvedMonths = SupportTicket::select(
                DB::raw("DATE_FORMAT(ticket_added_date, '%Y-%m') as month_value"),
                DB::raw("DATE_FORMAT(ticket_added_date, '%M %Y') as month_label"),
                DB::raw('count(*) as total')
            )
                ->where('status_id', \App\Models\SupportTicketStatus::RESOLVED)
                ->groupBy('month_value', 'month_label')
                ->orderBy('month_value', 'desc')
                ->get();
        }

        $query = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'latestLog.logger'])
            ->where('added_by', $user->user_id);

        // Filter by Status
        if ($stt == \App\Models\SupportTicketStatus::OPEN) {
            $query->where('status_id', \App\Models\SupportTicketStatus::OPEN);
        } elseif ($stt == \App\Models\SupportTicketStatus::IN_PROGRESS) {
            $query->where('status_id', \App\Models\SupportTicketStatus::IN_PROGRESS);
        } elseif ($stt == \App\Models\SupportTicketStatus::RESOLVED) {
            $query->where('status_id', \App\Models\SupportTicketStatus::RESOLVED);
            // Filter by Month if selected
            if ($request->filled('month')) {
                $query->where(DB::raw("DATE_FORMAT(ticket_added_date, '%Y-%m')"), $request->month);
            }
        } elseif ($stt == 4) { // 4 is a custom UI filter for Unassigned
            // Unassigned (Open and assigned_to = 0)
            $query->where('status_id', \App\Models\SupportTicketStatus::OPEN)
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

        return view('emp.tickets.index', compact('tickets', 'stt', 'categories', 'priorities', 'deptEmployees', 'resolvedMonths'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'added_by' => 'required|integer',
            'ticket_subject' => 'required|string|max:255',
            'ticket_description' => 'required|string',
            'category_id' => 'required|integer',
            'priority_id' => 'required|integer',
            'ticket_attachment' => 'nullable|file|max:8192', // 8MB max
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
            $extension = $file->getClientOriginalExtension();
            $filename = \Illuminate\Support\Str::random(64) . '.' . $extension;
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

        $ticket->status_id = \App\Models\SupportTicketStatus::OPEN;
        $ticket->assigned_to = 0;
        $ticket->save();

        // Create Initial Log
        $log = new \App\Models\SystemLog();
        $log->related_table = 'support_tickets_list';
        $log->related_id = $ticket->ticket_id;
        $log->log_action = 'Ticket Created';
        $log->log_remark = 'Ticket created by employee.';
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        // Send Notifications
        \App\Services\NotificationService::send(
            "A new ticket has been added, REF: " . $ticket->ticket_ref,
            "tickets/list",
            $ticket->added_by
        );

        // Notify IT Admin (Always ID 1 in legacy logic)
        \App\Services\NotificationService::send(
            "A new ticket has been added, REF: " . $ticket->ticket_ref,
            "tickets/list",
            1
        );

        return redirect()->route('emp.tickets.index')->with('success', 'Ticket created successfully');
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'logs.logger', 'latestLog.logger'])
            ->where('added_by', Auth::user()->user_id)
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

        $ticket = SupportTicket::where('added_by', $user->user_id)->findOrFail($id);
        $currentStatusId = (int)$ticket->status_id;
        $newStatusId = (int)$request->status_id;

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
        
        // Set end date if resolved
        if ($newStatusId == $statusResolved) {
            $ticket->ticket_end_date = now();
        }

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
    public function getData(Request $request)
    {
        $user = Auth::user();
        $stt = $request->input('stt', 0);
        $perPage = $request->get('per_page', 10);

        $query = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'latestLog.logger'])
            ->where('added_by', $user->user_id);

        if ($stt == \App\Models\SupportTicketStatus::OPEN) {
            $query->where('status_id', \App\Models\SupportTicketStatus::OPEN);
        } elseif ($stt == \App\Models\SupportTicketStatus::IN_PROGRESS) {
            $query->where('status_id', \App\Models\SupportTicketStatus::IN_PROGRESS);
        } elseif ($stt == \App\Models\SupportTicketStatus::RESOLVED) {
            $query->where('status_id', \App\Models\SupportTicketStatus::RESOLVED);
        } elseif ($stt == 4) {
            $query->where('status_id', \App\Models\SupportTicketStatus::OPEN)->where('assigned_to', 0);
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
