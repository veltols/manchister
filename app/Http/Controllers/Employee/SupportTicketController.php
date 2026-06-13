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

        // Monthly Resolved Stats (Current Year - January to December)
        $resolvedMonths = [];
        if ($stt == \App\Models\SupportTicketStatus::RESOLVED) {
            $currentYear = \Carbon\Carbon::now()->year;
            $counts = SupportTicket::select(
                DB::raw("DATE_FORMAT(ticket_added_date, '%Y-%m') as month_value"),
                DB::raw('count(*) as total')
            )
            ->where('status_id', \App\Models\SupportTicketStatus::RESOLVED)
            ->whereYear('ticket_added_date', $currentYear)
            ->where(function($q) use ($user) {
                $q->where('added_by', $user->user_id)
                  ->orWhere('assigned_to', $user->user_id);
            })
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

        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $query = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'latestLog.logger'])
            ->where(function($q) use ($user, $employeeId) {
                $q->where('added_by', $user->user_id)
                  ->orWhere('assigned_to', $user->user_id)
                  ->orWhere('approval_sent_to', $employeeId);
            });

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
        } elseif ($stt == \App\Models\SupportTicketStatus::CANCELLED) { // Cancelled
            $query->where('status_id', \App\Models\SupportTicketStatus::CANCELLED);
        } elseif ($stt == 5) {
            $query->whereIn('approval_status', ['pending_lm', 'pending_gm'])
                  ->where('approval_sent_to', $employeeId);
        }

        if ($request->filled('search')) {
            $query->where('ticket_ref', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('priority_id')) {
            $query->where('priority_id', $request->priority_id);
        }

        // Order by latest
        $tickets = $query->orderBy('ticket_id', 'desc')->paginate(10);

        // Data for "Create Ticket" Modal
        $categories = SupportTicketCategory::all();
        $priorities = Priority::all();
        
        $user = Auth::user();
        $deptId = $user->employee ? $user->employee->department_id : 0;
        
        $deptEmployees = \App\Models\Employee::where('department_id', $deptId)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) { $q->where('is_active', 1); })
            ->orderBy('first_name')
            ->get();
            
        $employees = \App\Models\Employee::where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) { $q->where('is_active', 1); })
            ->orderBy('first_name')->get();
            
        $itEmployees = \App\Models\Employee::where('department_id', 4)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) { $q->where('is_active', 1); })
            ->orderBy('first_name')->get();

        $groupedEmployees = \App\Models\Department::with(['employees' => function($q) {
            $q->where('is_deleted', 0)
              ->where('is_hidden', 0)
              ->whereHas('systemUser', function($sq) {
                  $sq->where('is_active', 1);
              })
              ->orderBy('first_name');
        }])->get();

        return view('emp.tickets.index', compact('tickets', 'stt', 'categories', 'priorities', 'deptEmployees', 'employees', 'itEmployees', 'resolvedMonths', 'groupedEmployees'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->user_type, ['root', 'sys_admin'])) {
            $request->merge(['added_by' => $user->employee->employee_id ?? 0]);
        }

        $request->validate([
            'added_by'           => 'required|exists:employees_list,employee_id',
            'assigned_to'        => 'nullable|exists:employees_list,employee_id',
            'ticket_subject'     => 'required|string|max:255',
            'ticket_description' => 'required|string',
            'category_id'        => 'required|integer',
            'priority_id'        => 'required|integer',
            'ticket_attachment'  => 'nullable|file|max:8192',
        ]);

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
        $ticket->department_id = $departmentId;
        $ticket->ticket_added_date = now();

        $ticket->status_id = \App\Models\SupportTicketStatus::OPEN;
        $ticket->assigned_to = $request->filled('assigned_to') ? (int) $request->assigned_to : 0;
        if ($ticket->assigned_to > 0) {
            $ticket->assigned_date = now();
        } else {
            $ticket->assigned_date = null;
        }
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
            "A new ticket has been created (Ref: $ticket->ticket_ref) and is pending admin review. The admin will assign it to the appropriate user after review.",
            "tickets/",
            $ticket->added_by
        );

        // Notify IT Admin (Always ID 1 in legacy logic)
        \App\Services\NotificationService::send(
            "A new ticket has been added, REF: " . $ticket->ticket_ref,
            "tickets",
            1
        );

        return redirect()->route('emp.tickets.index')->with('success', 'Ticket created successfully');
    }

    public function show($id)
    {
        $user = Auth::user();
        $userId = $user->user_id;
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $ticket = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'logs.logger', 'latestLog.logger', 'approvalApprover'])
            ->where(function($q) use ($userId, $employeeId) {
                $q->where('added_by', $userId)
                  ->orWhere('assigned_to', $userId)
                  ->orWhere('approval_sent_to', $employeeId);
            })
            ->findOrFail($id);

        $statuses = \App\Models\SupportTicketStatus::all();
        $priorities = \App\Models\Priority::all();
        
        $myDeptId = Auth::user()->employee ? Auth::user()->employee->department_id : 0;
        $employees = \App\Models\Employee::where('department_id', $myDeptId)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) { $q->where('is_active', 1); })
            ->orderBy('first_name')
            ->get();
            
        $allEmployees = \App\Models\Employee::where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) { $q->where('is_active', 1); })
            ->orderBy('first_name')->get();
            
        $itEmployees = \App\Models\Employee::where('department_id', 4)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->whereHas('systemUser', function($q) { $q->where('is_active', 1); })
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

        return view('emp.tickets.show', compact('ticket', 'statuses', 'priorities', 'employees', 'allEmployees', 'itEmployees', 'categories', 'groupedEmployees'));
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
            'log_remark'     => 'nullable|string',
            'category_id'    => 'required|exists:support_tickets_list_cats,category_id',
            'ticket_description' => 'required|string',
            'ticket_attachment'  => 'nullable|file|max:5120',
        ]);

        $userId = $user->user_id;
        $ticket = SupportTicket::where(function($q) use ($userId) {
                $q->where('added_by', $userId)
                  ->orWhere('assigned_to', $userId);
            })->findOrFail($id);

        $currentStatusId = (int)$ticket->status_id;
        $newStatusId = (int)$request->status_id;
        $statusResolved = \App\Models\SupportTicketStatus::RESOLVED;

        if ($currentStatusId != $newStatusId) {
            $statusOpen = \App\Models\SupportTicketStatus::OPEN;
            $statusInProgress = \App\Models\SupportTicketStatus::IN_PROGRESS;
            $statusCancelled = \App\Models\SupportTicketStatus::CANCELLED;

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
        }

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

        $log = new \App\Models\SystemLog();
        $log->related_table = 'support_tickets_list';
        $log->related_id = $ticket->ticket_id;
        $log->log_action = $logAction;
        $log->log_remark = $request->log_remark ?? 'Ticket details updated by Employee.';
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
            'log_remark' => 'required|string',
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $userId = $user->user_id;

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
        
        $logAction = 'Status Update';
        if ($request->has('assigned_to') && $request->assigned_to != "" && $request->assigned_to != $ticket->assigned_to) {
            $ticket->assigned_to = $request->assigned_to;
            $ticket->assigned_date = now();
            $logAction = 'Ticket Assigned';
        }
        
        // Set end date if resolved
        if ($newStatusId == $statusResolved) {
            $ticket->ticket_end_date = now();
        }

        $ticket->save();

        // Create Log
        $log = new \App\Models\SystemLog();
        $log->related_table = 'support_tickets_list';
        $log->related_id = $id;
        $log->log_action = $logAction;
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

        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $query = SupportTicket::with(['category', 'priority', 'status', 'addedBy', 'latestLog.logger'])
            ->where(function($q) use ($user, $employeeId) {
                $q->where('added_by', $user->user_id)
                  ->orWhere('assigned_to', $user->user_id)
                  ->orWhere('approval_sent_to', $employeeId);
            });

        if ($stt == \App\Models\SupportTicketStatus::OPEN) {
            $query->where('status_id', \App\Models\SupportTicketStatus::OPEN);
        } elseif ($stt == \App\Models\SupportTicketStatus::IN_PROGRESS) {
            $query->where('status_id', \App\Models\SupportTicketStatus::IN_PROGRESS);
        } elseif ($stt == \App\Models\SupportTicketStatus::RESOLVED) {
            $query->where('status_id', \App\Models\SupportTicketStatus::RESOLVED);
        } elseif ($stt == 4) {
            $query->where('status_id', \App\Models\SupportTicketStatus::OPEN)->where('assigned_to', 0);
        } elseif ($stt == 5) {
            $query->whereIn('approval_status', ['pending_lm', 'pending_gm'])
                  ->where('approval_sent_to', $employeeId);
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

    public function approve(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'nullable|string'
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $ticket = SupportTicket::findOrFail($id);

        if ((int)$ticket->approval_sent_to !== $employeeId || !in_array($ticket->approval_status, ['pending_lm', 'pending_gm'])) {
            return redirect()->back()->with('error', 'Unauthorized — this ticket is not awaiting your approval.');
        }

        $ticket->approval_status = 'approved';
        $ticket->approval_remarks = $request->remarks;
        $ticket->approval_action_date = now();
        $ticket->save();

        // Log System Action
        $log = new \App\Models\SystemLog();
        $log->related_table = 'support_tickets_list';
        $log->related_id = $ticket->ticket_id;
        $log->log_action = 'Ticket Approved';
        $log->log_remark = 'Approved by manager. Remarks: ' . ($request->remarks ?? 'None');
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        // Notify Admin (ID 1)
        \App\Services\NotificationService::send(
            "Ticket (Ref: {$ticket->ticket_ref}) has been APPROVED.",
            "tickets/{$ticket->ticket_id}",
            1
        );

        // Notify Creator
        \App\Services\NotificationService::send(
            "Your ticket (Ref: {$ticket->ticket_ref}) has been APPROVED.",
            "tickets/{$ticket->ticket_id}",
            $ticket->added_by
        );

        return redirect()->back()->with('success', 'Ticket approved successfully.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'required|string|min:5'
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $ticket = SupportTicket::findOrFail($id);

        if ((int)$ticket->approval_sent_to !== $employeeId || !in_array($ticket->approval_status, ['pending_lm', 'pending_gm'])) {
            return redirect()->back()->with('error', 'Unauthorized — this ticket is not awaiting your approval.');
        }

        $ticket->approval_status = 'rejected';
        $ticket->approval_remarks = $request->remarks;
        $ticket->approval_action_date = now();
        $ticket->status_id = \App\Models\SupportTicketStatus::CANCELLED;
        $ticket->save();

        // Log System Action
        $log = new \App\Models\SystemLog();
        $log->related_table = 'support_tickets_list';
        $log->related_id = $ticket->ticket_id;
        $log->log_action = 'Ticket Rejected';
        $log->log_remark = 'Rejected by manager. Remarks: ' . $request->remarks;
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        // Notify Admin (ID 1)
        \App\Services\NotificationService::send(
            "Ticket (Ref: {$ticket->ticket_ref}) has been REJECTED.",
            "tickets/{$ticket->ticket_id}",
            1
        );

        // Notify Creator
        \App\Services\NotificationService::send(
            "Your ticket (Ref: {$ticket->ticket_ref}) has been REJECTED. Remarks: {$request->remarks}",
            "tickets/{$ticket->ticket_id}",
            $ticket->added_by
        );

        return redirect()->back()->with('success', 'Ticket rejected successfully.');
    }
}
