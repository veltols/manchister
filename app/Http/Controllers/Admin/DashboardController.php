<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SupportTicket;
use App\Models\Asset;
use App\Models\Department;
use App\Models\SupportTicketCategory; // Using Theme/Category models if available, or raw queries
use App\Models\AssetCategory;

class DashboardController extends Controller
{
    public function index()
    {
        // --- TICKETS STATS ---
        $totalTickets = SupportTicket::count();
        $totalOpen = SupportTicket::where('status_id', 1)->count(); // 1 = Open
        $totalUnassigned = SupportTicket::where('status_id', 1)->where('assigned_to', 0)->count();
        $totalProgress = SupportTicket::where('status_id', 2)->count(); // 2 = In Progress
        $totalResolved = SupportTicket::where('status_id', 3)->count(); // 3 = Resolved

        // --- ASSETS STATS ---
        $totalAssets = Asset::count();
        $totalAssetsInStock = Asset::where('status_id', 1)->count(); // 1 = In Stock
        $totalAssetsInUse = Asset::where('status_id', 2)->count();   // 2 = In Use
        $totalAssetsRetired = Asset::where('status_id', 3)->count(); // 3 = Retired

        // --- RECENT TICKETS ---
        $recentTickets = DB::table('support_tickets_list')
            ->join('employees_list', 'support_tickets_list.added_by', '=', 'employees_list.employee_id')
            ->select(
                'support_tickets_list.*',
                DB::raw("CONCAT(employees_list.first_name, ' ', employees_list.last_name) as added_employee")
            )
            ->orderBy('support_tickets_list.ticket_id', 'desc')
            ->limit(5)
            ->get();


        // --- CHARTS DATA PREP ---
        
        // 1. Tickets by Department
        $ticketsByDeptLabels = [];
        $ticketsByDeptCounts = [];
        $departments = Department::all();
        foreach($departments as $dept) {
            $count = SupportTicket::where('department_id', $dept->department_id)->count();
            if($count > 0) {
                $ticketsByDeptLabels[] = $dept->department_name;
                $ticketsByDeptCounts[] = $count;
            }
        }

        // 2. Tickets by Priority (Theme)
        // Legacy uses 'sys_list_priorities' table, we can query it directly or use model if exists
        $ticketsByPriorityLabels = [];
        $ticketsByPriorityCounts = [];
        $priorities = DB::table('sys_list_priorities')->get(); 
        foreach($priorities as $prio) {
            $count = SupportTicket::where('priority_id', $prio->priority_id)->count();
             if($count > 0) {
                $ticketsByPriorityLabels[] = $prio->priority_name;
                $ticketsByPriorityCounts[] = $count;
            }
        }

        // 3. Assets by Department
        $assetsByDeptLabels = [];
        $assetsByDeptCounts = [];
        foreach($departments as $dept) {
            $count = Asset::where('department_id', $dept->department_id)->count();
            if($count > 0) {
                $assetsByDeptLabels[] = $dept->department_name;
                $assetsByDeptCounts[] = $count;
            }
        }

        // 4. Assets by Category
        $assetsByCatLabels = [];
        $assetsByCatCounts = [];
        $assetCats = DB::table('z_assets_list_cats')->get();
        foreach($assetCats as $cat) {
            $count = Asset::where('category_id', $cat->category_id)->count();
            if($count > 0) {
                $assetsByCatLabels[] = $cat->category_name;
                $assetsByCatCounts[] = $count;
            }
        }


        return view('admin.dashboard.index', compact(
            'totalTickets', 'totalOpen', 'totalUnassigned', 'totalProgress', 'totalResolved',
            'totalAssets', 'totalAssetsInStock', 'totalAssetsInUse', 'totalAssetsRetired',
            'recentTickets',
            'ticketsByDeptLabels', 'ticketsByDeptCounts',
            'ticketsByPriorityLabels', 'ticketsByPriorityCounts',
            'assetsByDeptLabels', 'assetsByDeptCounts',
            'assetsByCatLabels', 'assetsByCatCounts'
        ));
    }
}
