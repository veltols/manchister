<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AssetCategory;

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
        $cats = AssetCategory::all();
        return view('admin.settings.asset_categories', compact('cats'));
    }

    public function storeAssetCategory(Request $request)
    {
        $request->validate(['category_name' => 'required|string']);
        $cat = new AssetCategory();
        $cat->category_name = $request->category_name;
        $cat->category_alert_days = $request->category_alert_days ?? 0;
        $cat->category_color = $request->category_color ?? '#000000';
        $cat->save();
        return redirect()->back()->with('success', 'Category Added');
    }
}
