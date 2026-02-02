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
    public function index()
    {
        $tickets = SupportTicket::with(['category', 'priority', 'status', 'employee'])
            ->orderBy('ticket_id', 'desc')
            ->paginate(10);

        return view('hr.tickets.index', compact('tickets'));
    }

    public function create()
    {
        // Assuming categories and priorities exists in sys_lists or similar models
        // Using generic names or specific models if available.
        // I will use SupportTicketCategory and Priority models if they exist.
        // Checked models list: SupportTicketCategory exists, Priority exists.

        $categories = SupportTicketCategory::all();
        $priorities = Priority::all();

        return view('hr.tickets.create', compact('categories', 'priorities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ticket_subject' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'priority_id' => 'required|integer',
            'ticket_description' => 'required|string',
        ]);

        $ticket = new SupportTicket();
        $ticket->ticket_subject = $request->ticket_subject;
        $ticket->category_id = $request->category_id;
        $ticket->priority_id = $request->priority_id;
        $ticket->ticket_description = $request->ticket_description;

        $ticket->ticket_status_id = 1; // Open/New
        $ticket->employee_id = Auth::id() ?? 0;
        $ticket->created_date = now();
        $ticket->ticket_ref = 'TKT-' . date('Ymd') . '-' . rand(100, 999);

        $ticket->save();

        return redirect()->route('hr.tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['category', 'priority', 'status', 'employee'])->findOrFail($id);
        return view('hr.tickets.show', compact('ticket'));
    }
}
