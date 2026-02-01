<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HrDocument;
use App\Models\SupportTicket;
use App\Models\Asset;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // --- Date Logic (Default to This Month) ---
        $mode = $request->input('mode', 'this_month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($mode == 'today') {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($mode == 'this_week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } elseif ($mode == 'this_month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } else {
            // Custom or default fallback
            $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
            $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();
        }

        // --- 1. Announcements (Carousel & List) ---
        $announcements = HrDocument::where('document_type_id', 3)
            ->orderBy('document_id', 'desc')
            ->take(5)
            ->get();

        // --- 2. Tickets Stats ---
        // Status IDs: 1=Open, 2=In Progress, 3=Resolved
        // Unassigned: assigned_to = 0
        $totalTickets = SupportTicket::whereBetween('ticket_added_date', [$startDate, $endDate])->count();
        
        $openTickets = SupportTicket::where('status_id', 1)
            ->whereBetween('ticket_added_date', [$startDate, $endDate])
            ->count();
            
        $unassignedTickets = SupportTicket::where('assigned_to', 0)
            ->where('status_id', 1)
            ->whereBetween('ticket_added_date', [$startDate, $endDate])
            ->count();

        $progressTickets = SupportTicket::where('status_id', 2)
            ->whereBetween('ticket_added_date', [$startDate, $endDate])
            ->count();

        $resolvedTickets = SupportTicket::where('status_id', 3)
            ->whereBetween('ticket_added_date', [$startDate, $endDate])
            ->count();

        $ticketStats = compile_stats(
            total: $totalTickets,
            open: $openTickets,
            unassigned: $unassignedTickets,
            progress: $progressTickets,
            resolved: $resolvedTickets
        );

        // --- 3. My Assets ---
        // Legacy query showed all, but we should tentatively try to filter or just show what legacy showed.
        // Legacy: SELECT ... FROM z_assets_list JOIN employees_list ... LIMIT 5
        // We will show 5 assets for now.
        $assets = Asset::with('assignedBy')
            ->orderBy('asset_id', 'asc')
            ->take(5)
            ->get();

        // --- 4. HR Stats (Placeholders as per legacy) ---
        $hrStats = [
            'leave_balance' => 0,
            'remaining_leaves' => 0,
            'requests' => 0,
            'pending_approval' => 0,
        ];

        return view('emp.dashboard.index', compact(
            'user', 
            'announcements', 
            'ticketStats', 
            'assets', 
            'hrStats',
            'mode',
            'startDate',
            'endDate'
        ));
    }
}

function compile_stats($total, $open, $unassigned, $progress, $resolved) {
    return (object) compact('total', 'open', 'unassigned', 'progress', 'resolved');
}
