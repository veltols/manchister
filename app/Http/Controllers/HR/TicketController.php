<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TaskPriority;
use App\Models\TaskStatus;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $employeeId = optional(Auth::user()->employee)->employee_id ?? 0;

        $tickets = \App\Models\SupportTicket::with(['category', 'priority', 'status', 'addedBy'])
            ->orderBy('ticket_id', 'desc')
            ->get();

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

        $ticket = new \App\Models\SupportTicket();
        $ticket->ticket_ref = 'T-' . time();
        $ticket->ticket_subject = $request->ticket_subject;
        $ticket->ticket_description = $request->ticket_description;
        $ticket->category_id = $request->category_id;
        $ticket->priority_id = $request->priority_id;
        $ticket->added_by = $request->added_by;
        $ticket->added_date = now();
        // Assuming theme_id maps to priority_id in form? legacy uses theme_id input name for priority

        $ticket->status_id = 1; // Default Pending/Open

        if ($request->hasFile('ticket_attachment')) {
            $file = $request->file('ticket_attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $ticket->ticket_attachment = $fileName;
        }

        $ticket->save();

        return response()->json(['success' => true, 'message' => 'Ticket created successfully']);
    }
}
