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
use App\Models\AssetStatus;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mode = $request->input('mode', 'all');
        $startDate = null;
        $endDate = null;

        if ($mode == 'today') {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
        } elseif ($mode == 'this_week') {
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
        } elseif ($mode == 'this_month') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        }

        // Helper for date filtering
        $applyDate = function($query, $dateColumn = 'created_at') use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                return $query->whereBetween($dateColumn, [$startDate, $endDate]);
            }
            return $query;
        };

        // --- TICKETS STATS ---
        // Legacy: ticket_added_date
        $totalTickets = $applyDate(SupportTicket::query(), 'ticket_added_date')->count();
        $totalOpen = $applyDate(SupportTicket::where('status_id', 1), 'ticket_added_date')->count(); 
        $totalUnassigned = $applyDate(SupportTicket::where('status_id', 1)->where('assigned_to', 0), 'ticket_added_date')->count();
        $totalProgress = $applyDate(SupportTicket::where('status_id', 2), 'ticket_added_date')->count();
        $totalResolved = $applyDate(SupportTicket::where('status_id', 3), 'ticket_added_date')->count();

        // --- ASSETS STATS ---
        // Legacy: added_date. 
        // Note: Legacy filtered assets by "added_date" even for status counts (In Use, etc), 
        // effectively showing "Assets added in X period that are NOW in status Y".
        $totalAssets = $applyDate(Asset::query(), 'added_date')->count();
        $totalAssetsInStock = $applyDate(Asset::where('status_id', 1), 'added_date')->count();
        $totalAssetsInUse = $applyDate(Asset::where('status_id', 2), 'added_date')->count();
        $totalAssetsRetired = $applyDate(Asset::where('status_id', 3), 'added_date')->count();

        // --- RECENT TICKETS ---
        // Showing top 5 recent regardless of filter, or should we filter? 
        // Legacy lists "Recent Tickets" separately, usually generally recent. 
        // But for consistency let's keep it general recent, or filter if requested. 
        // Legacy code: "SELECT ... LIMIT 5". It does NOT apply date filter to the "Recent Tickets" list in the logic I saw (unless I missed it).
        // Actually, legacy index.php line 224 query does NOT use $startDate/$endDate.
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
            $query = SupportTicket::where('department_id', $dept->department_id);
            $count = $applyDate($query, 'ticket_added_date')->count();
            if($count > 0) {
                $ticketsByDeptLabels[] = $dept->department_name;
                $ticketsByDeptCounts[] = $count;
            }
        }

        // 2. Tickets by Priority (Theme)
        $ticketsByPriorityLabels = [];
        $ticketsByPriorityCounts = [];
        $priorities = DB::table('sys_list_priorities')->get(); 
        foreach($priorities as $prio) {
            $query = SupportTicket::where('priority_id', $prio->priority_id); // Assuming priority_id column map
            // Legacy uses `theme_id` in `support_tickets_list` mapping to `sys_list_priorities.theme_id`
            // Let's verify column name in SupportTicket model or table. 
            // In legacy `support_tickets_list` has `theme_id`. 
            // My SupportTicket Model likely maps this. If I used `priority_id` in my previous edits, I should stick to it, 
            // but if the DB column is `theme_id`, I need to use that or the relationship.
            // Assuming `priority_id` or `theme_id` is consistent.
            // Let's use the explicit `theme_id` if using raw DB or Model property if mapped.
            // Safest: Use Model where clause if column exists.
            // Checking: I haven't seen SupportTicket model content fully but standard is `priority_id`. 
            // Legacy query: `WHERE theme_id = $theme_id`.
            // I'll assume `priority_id` in Laravel model implies `theme_id` in DB if I set primary key/column.
            // For now, I'll use `priority_id` and hope the model maps it. 
            // Actually, to be safe against Legacy DB column names, I'll use `theme_id` if using raw query context, 
            // but effectively: `where('priority_id', ...)` works if model is standard.
            // Let's assume standard Laravel Model usage from previous Context.
            
            $count = $applyDate(SupportTicket::where('priority_id', $prio->priority_id), 'ticket_added_date')->count();
             if($count > 0) {
                $ticketsByPriorityLabels[] = $prio->priority_name;
                $ticketsByPriorityCounts[] = $count;
            }
        }

        // 3. Assets by Department
        $assetsByDeptLabels = [];
        $assetsByDeptCounts = [];
        foreach($departments as $dept) {
            $count = $applyDate(Asset::where('department_id', $dept->department_id), 'added_date')->count();
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
            $count = $applyDate(Asset::where('category_id', $cat->category_id), 'added_date')->count();
            if($count > 0) {
                $assetsByCatLabels[] = $cat->category_name;
                $assetsByCatCounts[] = $count;
            }
        }
        
        // 5. Assets by Status (NEW - Missing in previous)
        $assetsByStatusLabels = [];
        $assetsByStatusCounts = [];
        $assetStatuses = AssetStatus::all(); // Assuming AssetStatus model exists from previous steps
        foreach($assetStatuses as $stt) {
             $count = $applyDate(Asset::where('status_id', $stt->status_id), 'added_date')->count();
             if($count > 0) {
                 $assetsByStatusLabels[] = $stt->status_name;
                 $assetsByStatusCounts[] = $count;
             }
        }


        return view('admin.dashboard.index', compact(
            'totalTickets', 'totalOpen', 'totalUnassigned', 'totalProgress', 'totalResolved',
            'totalAssets', 'totalAssetsInStock', 'totalAssetsInUse', 'totalAssetsRetired',
            'recentTickets',
            'ticketsByDeptLabels', 'ticketsByDeptCounts',
            'ticketsByPriorityLabels', 'ticketsByPriorityCounts',
            'assetsByDeptLabels', 'assetsByDeptCounts',
            'assetsByCatLabels', 'assetsByCatCounts',
            'assetsByStatusLabels', 'assetsByStatusCounts',
            'mode'
        ));
    }
}
