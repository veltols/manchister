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

        // --- 4. HR Stats (Real Data) ---
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $hrStats = [
            'leave_balance' => 0, // Need logic for balance
            'remaining_leaves' => 0,
            'requests' => \Illuminate\Support\Facades\DB::table('hr_employees_leaves')->where('employee_id', $employeeId)->count(),
            'pending_approval' => \Illuminate\Support\Facades\DB::table('hr_employees_leaves')->where('employee_id', $employeeId)->where('leave_status_id', 2)->count(),
        ];

        // --- 5. Task Stats ---
        $myTasksQuery = \App\Models\Task::where('assigned_to', $employeeId);
        
        $taskStats = [
            'total' => (clone $myTasksQuery)->count(),
            'todo' => (clone $myTasksQuery)->where('status_id', 1)->count(),
            'progress' => (clone $myTasksQuery)->where('status_id', 2)->count(),
            'done' => (clone $myTasksQuery)->where('status_id', 3)->count(),
            'overdue' => (clone $myTasksQuery)->where('status_id', '!=', 3)
                ->where('task_due_date', '<', now())
                ->count(),
        ];

        $recentTasks = (clone $myTasksQuery)->with(['status', 'priority'])
            ->orderBy('task_id', 'desc')
            ->take(5)
            ->get();

        $employeeName = $user->employee ? ($user->employee->first_name . ' ' . $user->employee->last_name) : $user->user_email;

        return view('emp.dashboard.index', compact(
            'user',
            'employeeName',
            'announcements',
            'ticketStats',
            'assets',
            'hrStats',
            'taskStats',
            'recentTasks',
            'mode',
            'startDate',
            'endDate'
        ));
    }
}

function compile_stats($total, $open, $unassigned, $progress, $resolved)
{
    return (object) compact('total', 'open', 'unassigned', 'progress', 'resolved');
}
