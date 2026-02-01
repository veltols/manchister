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
        $stt = $request->input('stt', 0); // 0=All, 1=Open, 2=In Progress, 3=Resolved

        $query = SupportTicket::query();

        // Join for readable names (optional, or use Eloquent relationships)
        $query->leftJoin('support_tickets_list_cats as c', 'support_tickets_list.category_id', '=', 'c.category_id')
              ->leftJoin('sys_list_priorities as p', 'support_tickets_list.priority_id', '=', 'p.priority_id')
              ->leftJoin('sys_list_status as s', 'support_tickets_list.status_id', '=', 's.status_id')
              ->select(
                  'support_tickets_list.*',
                  'c.category_name',
                  'p.priority_name', 'p.priority_color',
                  's.status_name as status_name', 's.status_color'
              );

        // Filter by Status
        if ($stt == 1) {
            $query->where('support_tickets_list.status_id', 1);
        } elseif ($stt == 2) {
            $query->where('support_tickets_list.status_id', 2);
        } elseif ($stt == 3) {
            $query->where('support_tickets_list.status_id', 3);
        }

        // Order by latest
        $tickets = $query->orderBy('ticket_id', 'desc')->paginate(10);

        // Data for "Create Ticket" Modal
        $categories = SupportTicketCategory::all();
        $priorities = Priority::all();
        
        // Employees in same department (for 'assigned_by' or similar fields if needed)
        // In legacy: SELECT ... FROM employees_list WHERE department_id = $myDeptId
        $myDeptId = 1; // Default fallback
        if ($user->employee) {
            $myDeptId = $user->employee->department_id;
        }
        $deptEmployees = Employee::where('department_id', $myDeptId)
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->get();

        return view('emp.tickets.index', compact('tickets', 'stt', 'categories', 'priorities', 'deptEmployees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ticket_subject' => 'required|string|max:255',
            'ticket_description' => 'required|string',
            'category_id' => 'required|integer',
            'priority_id' => 'required|integer',
            'ticket_attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $departmentId = $user->employee ? $user->employee->department_id : 1;

        // Upload attachment if exists
        $attachmentPath = '';
        if ($request->hasFile('ticket_attachment')) {
            $file = $request->file('ticket_attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/tickets'), $filename);
            $attachmentPath = 'uploads/tickets/' . $filename;
        }

        // Generate Ticket REF (Legacy style or simple random)
        $ref = 'T-' . time();

        $ticket = new SupportTicket();
        $ticket->ticket_ref = $ref;
        $ticket->ticket_subject = $request->ticket_subject;
        $ticket->ticket_description = $request->ticket_description;
        $ticket->category_id = $request->category_id;
        $ticket->priority_id = $request->priority_id;
        $ticket->ticket_attachment = $attachmentPath;
        
        $ticket->added_by = $employeeId; // Logged in user
        $ticket->department_id = $departmentId;
        $ticket->ticket_added_date = now();
        
        $ticket->status_id = 1; // Open
        $ticket->assigned_to = 0; // Unassigned
        $ticket->save();

        return redirect()->route('emp.tickets.index')->with('success', 'Ticket created successfully');
    }
}
