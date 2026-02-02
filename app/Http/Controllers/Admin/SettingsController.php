<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AssetCategory;
use App\Models\AssetStatus;
use App\Models\Employee;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    // Leave Types
    public function leaveTypes()
    {
        $types = DB::table('hr_employees_leave_types')->orderBy('leave_type_id', 'desc')->get();
        return view('admin.settings.leave_types', compact('types'));
    }

    public function storeLeaveType(Request $request)
    {
        $request->validate(['leave_type_name' => 'required|string']);
        DB::table('hr_employees_leave_types')->insert([
            'leave_type_name' => $request->leave_type_name,
            'is_paid' => $request->is_paid ?? 0,
            'days_per_year' => $request->days_per_year ?? 0
        ]);
        return redirect()->back()->with('success', 'Leave Type Added');
    }

    // Asset Categories
    public function assetCategories()
    {
        $cats = AssetCategory::orderBy('category_name')->get();
        return view('admin.settings.asset_categories', compact('cats'));
    }

    public function storeAssetCategory(Request $request)
    {
        $request->validate(['category_name' => 'required|string']);
        $cat = new AssetCategory();
        $cat->category_name = $request->category_name;
        $cat->category_alert_days = $request->category_alert_days ?? 0; // from root/settings/ac.php
        $cat->save();
        return redirect()->back()->with('success', 'Category Added');
    }

    // Asset Statuses (as.php)
    public function assetStatuses()
    {
        $statuses = AssetStatus::orderBy('status_name')->get();
        return view('admin.settings.asset_statuses', compact('statuses'));
    }

    public function storeAssetStatus(Request $request)
    {
        $request->validate(['status_name' => 'required|string']);
        $st = new AssetStatus();
        $st->status_name = $request->status_name;
        $st->save();
        return redirect()->back()->with('success', 'Status Added');
    }

    // Support Service Categories (ss.php)
    public function supportCategories()
    {
        // Assuming table 'gen_requests_cats' based on standard naming
        $cats = DB::table('gen_requests_cats')
            ->leftJoin('employees_list', 'gen_requests_cats.destination_id', '=', 'employees_list.employee_id')
            ->select('gen_requests_cats.*', 'employees_list.first_name', 'employees_list.last_name')
            ->orderBy('category_name')
            ->get();

        $employees = Employee::where('is_deleted', 0)->orderBy('first_name')->get();

        return view('admin.settings.support_categories', compact('cats', 'employees'));
    }

    public function storeSupportCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string',
            'destination_id' => 'required|integer'
        ]);

        DB::table('gen_requests_cats')->insert([
            'category_name' => $request->category_name,
            'destination_id' => $request->destination_id
        ]);

        return redirect()->back()->with('success', 'Category Added');
    }

    // Priorities (pp.php) like Task Priorities
    public function priorities()
    {
        // Assuming 'tasks_list_priorities' or similar
        $priorities = DB::table('tasks_list_priorities')->orderBy('priority_name')->get();
        return view('admin.settings.priorities', compact('priorities'));
    }

    public function storePriority(Request $request)
    {
        $request->validate(['priority_name' => 'required|string']);
        DB::table('tasks_list_priorities')->insert([
            'priority_name' => $request->priority_name,
            'priority_color' => '#000000' // Default
        ]);
        return redirect()->back()->with('success', 'Priority Added');
    }
}
