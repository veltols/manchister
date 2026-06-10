<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportTicketCategory;
use App\Models\Priority;
use App\Models\SupportTicketStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $stt = $request->input('stt', 0); // 0=All, 1=Open, 2=In Progress, 3=Resolved, 4=Unassigned

        // Monthly Resolved Stats (Current Year - January to December)
        $resolvedMonths = [];
        if ($stt == SupportTicketStatus::RESOLVED) {
            $currentYear = \Carbon\Carbon::now()->year;
            $counts = SupportTicket::select(
                DB::raw("DATE_FORMAT(ticket_added_date, '%Y-%m') as month_value"),
                DB::raw('count(*) as total')
            )
            ->where('status_id', SupportTicketStatus::RESOLVED)
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

        $query = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'latestLog.logger']);

        // Filter by Status
        if ($stt == SupportTicketStatus::OPEN) {
            $query->where('status_id', SupportTicketStatus::OPEN);
        } elseif ($stt == SupportTicketStatus::IN_PROGRESS) {
            $query->where('status_id', SupportTicketStatus::IN_PROGRESS);
        } elseif ($stt == SupportTicketStatus::RESOLVED) {
            $query->where('status_id', SupportTicketStatus::RESOLVED);
            // Filter by Month if selected
            if ($request->filled('month')) {
                $query->where(DB::raw("DATE_FORMAT(ticket_added_date, '%Y-%m')"), $request->month);
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

        $tickets = $query->orderBy('ticket_id', 'desc')->paginate(10);

        // Data for "Create Ticket" Modal
        $categories = SupportTicketCategory::all();
        $priorities = Priority::all();
        $employees = \App\Models\Employee::where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('first_name')->get();
        $itEmployees = \App\Models\Employee::where('department_id', 4)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('first_name')->get();

        $groupedEmployees = \App\Models\Department::with(['employees' => function($q) {
            $q->where('is_deleted', 0)
              ->where('is_hidden', 0)
              ->whereHas('systemUser', function($sq) {
                  $sq->where('is_active', 1);
              })
              ->orderBy('first_name');
        }])->get();

        return view('hr.tickets.index', compact('tickets', 'stt', 'categories', 'priorities', 'employees', 'itEmployees', 'resolvedMonths', 'groupedEmployees'));
    }

    public function create()
    {
        $categories = SupportTicketCategory::all();
        $priorities = Priority::all();
        return view('hr.tickets.create', compact('categories', 'priorities'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->user_type, ['root', 'sys_admin'])) {
            $request->merge(['added_by' => $user->employee->employee_id ?? 0]);
        }

        $request->validate([
            'added_by'           => 'required|exists:employees_list,employee_id',
            'assigned_to'        => 'required|exists:employees_list,employee_id',
            'ticket_subject'     => 'required|string|max:255',
            'ticket_description' => 'required|string',
            'category_id'        => 'required|integer',
            'priority_id'        => 'required|integer',
            'ticket_attachment'  => 'nullable|file|max:8192',
        ]);

        // Upload attachment if exists
        $attachmentName = 'no-img.png';
        if ($request->hasFile('ticket_attachment')) {
            $file = $request->file('ticket_attachment');
            $extension = $file->getClientOriginalExtension();
            $filename = \Illuminate\Support\Str::random(64) . '.' . $extension;
            $uploadDir = public_path('uploads/tickets');
            if (!file_exists($uploadDir)) { mkdir($uploadDir, 0755, true); }
            $file->move($uploadDir, $filename);
            $attachmentName = 'uploads/tickets/' . $filename;
        }

        // Generate Ticket REF: TK-YYMM001 style
        $ref = SupportTicket::generateReference();

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
        $ticket->status_id = SupportTicketStatus::OPEN;
        $ticket->assigned_to = (int) $request->assigned_to;
        $ticket->assigned_date = now();
        $ticket->save();

        // Create Initial Log
        $log = new \App\Models\SystemLog();
        $log->related_table = 'support_tickets_list';
        $log->related_id = $ticket->ticket_id;
        $log->log_action = 'Ticket Created';
        $log->log_remark = 'Ticket created by HR for employee.';
        $log->log_date = now();
        $log->logged_by = $user->employee->employee_id ?? 0;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        // Send Notifications
        // 1. Notify the employee who the ticket was added for
        \App\Services\NotificationService::send(
            "A new ticket has been created for you by HR, REF: " . $ticket->ticket_ref,
            "tickets",
            $ticket->added_by
        );

        // 2. Notify IT Admin (System Admin)
        \App\Services\NotificationService::send(
            "A new ticket has been added by HR, REF: " . $ticket->ticket_ref,
            "tickets",
            1
        );

        return redirect()->route('hr.tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function show($id)
    {
        $userId = Auth::user()->user_id;
        $ticket = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'logs.logger', 'latestLog.logger'])
            ->where(function($q) use ($userId) {
                $q->where('added_by', $userId)
                  ->orWhere('assigned_to', $userId);
            })
            ->findOrFail($id);

        $statuses = \App\Models\SupportTicketStatus::all();
        $priorities = \App\Models\Priority::all();
        $allEmployees = \App\Models\Employee::where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('first_name')->get();
        $employees = $allEmployees; // HR can see all employees
        $itEmployees = \App\Models\Employee::where('department_id', 4)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('first_name')->get();
        $categories = SupportTicketCategory::all();

        $groupedEmployees = \App\Models\Department::with(['employees' => function($q) {
            $q->where('is_deleted', 0)
              ->where('is_hidden', 0)
              ->whereHas('systemUser', function($sq) {
                  $sq->where('is_active', 1);
              })
              ->orderBy('first_name');
        }])->get();

        return view('hr.tickets.show', compact('ticket', 'statuses', 'priorities', 'employees', 'allEmployees', 'itEmployees', 'categories', 'groupedEmployees'));
    }

    public function updateDetails(Request $request, $id)
    {
        $user = Auth::user();
        if (!in_array($user->user_type, ['root', 'sys_admin'])) {
            $request->merge(['added_by' => $user->employee->employee_id ?? 0]);
        }

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

        $userId = $user->user_id;
        $ticket = SupportTicket::where(function($q) use ($userId) {
                $q->where('added_by', $userId)
                  ->orWhere('assigned_to', $userId);
            })->findOrFail($id);

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

        // Update department if the reporter changed
        $employee = \App\Models\Employee::find($request->added_by);
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

        // Create Log
        $log = new \App\Models\SystemLog();
        $log->related_table = 'support_tickets_list';
        $log->related_id = $ticket->ticket_id;
        $log->log_action = $logAction;
        $log->log_remark = $request->ticket_remarks ?? 'Subject, Priority, Status or Reporter was updated by HR.';
        $log->log_date = now();
        $log->logged_by = Auth::user()->employee->employee_id ?? 0;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|integer',
            'ticket_remarks' => 'required|string',
        ]);

        $userId = Auth::user()->user_id;
        $ticket = SupportTicket::where(function($q) use ($userId) {
                $q->where('added_by', $userId)
                  ->orWhere('assigned_to', $userId);
            })->findOrFail($id);
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
        } else {
            // If status is changed from Resolved to something else (e.g., Open/Reopened), clear end date
            if ($currentStatusId == $statusResolved && $newStatusId == $statusOpen) {
                $ticket->ticket_end_date = null;
            }
        }

        $logAction = "Status Updated";
        if ($newStatusId == $statusInProgress && $currentStatusId != $statusInProgress) {
            $logAction = "Ticket In Progress";
        } elseif ($newStatusId == $statusResolved && $currentStatusId != $statusResolved) {
            $logAction = "Ticket Resolved";
        } elseif ($newStatusId == $statusCancelled && $currentStatusId != $statusCancelled) {
            $logAction = "Ticket Cancelled";
        } elseif ($newStatusId == $statusOpen && ($currentStatusId == $statusResolved || $currentStatusId == $statusCancelled)) {
            $logAction = "Ticket Reopened";
        }

        if ($request->has('assigned_to') && $request->assigned_to != "" && $request->assigned_to != $ticket->assigned_to) {
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
            "Your ticket status has been updated to " . ($ticket->status ? $ticket->status->status_name : 'Updated') . ", REF: " . $ticket->ticket_ref,
            "tickets",
            $ticket->added_by
        );

        // Notify Assignee if assigned
        if ($ticket->assigned_to && $ticket->assigned_to != 0) {
            \App\Services\NotificationService::send(
                "A ticket has been assigned to you, REF: " . $ticket->ticket_ref,
                "tickets",
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

        if ($stt == SupportTicketStatus::OPEN) {
            $query->where('status_id', SupportTicketStatus::OPEN);
        } elseif ($stt == SupportTicketStatus::IN_PROGRESS) {
            $query->where('status_id', SupportTicketStatus::IN_PROGRESS);
        } elseif ($stt == SupportTicketStatus::RESOLVED) {
            $query->where('status_id', SupportTicketStatus::RESOLVED);
        } elseif ($stt == 4) {
            $query->where('status_id', SupportTicketStatus::OPEN)->where('assigned_to', 0);
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
