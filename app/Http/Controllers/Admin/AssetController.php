<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetStatus;
use App\Models\Employee;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $stt = $request->input('stt', 0); // 0=All, 1=About to Expire, 2=Expired

        $query = Asset::with(['category', 'assignee', 'assignedBy']);

        if ($stt == 1) {
            // About to expire (next 30 days)
            $query->whereBetween('expiry_date', [Carbon::now(), Carbon::now()->addDays(30)]);
        } elseif ($stt == 2) {
            // Expired
            $query->where('expiry_date', '<', Carbon::now());
        }

        $assets = $query->orderBy('asset_id', 'desc')->paginate(15);

        // Data for Create Modal
        $categories = AssetCategory::all();
        $statuses = AssetStatus::all();
        $employees = Employee::where('is_deleted', 0)->where('is_hidden', 0)->orderBy('first_name')->get();

        return view('admin.assets.index', compact('assets', 'stt', 'categories', 'statuses', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_name' => 'required|string|max:255',
            'category_id' => 'required|exists:z_assets_list_cats,category_id',
            'asset_serial' => 'required|string|max:100',
            'status_id' => 'required|exists:z_assets_list_status,status_id',
            'description' => 'required|string',
            'purchase_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
        ]);

        $asset = new Asset();
        $asset->asset_ref = 'AST-' . strtoupper(uniqid());
        $asset->asset_sku = '132'; // Legacy default
        $asset->asset_name = $request->asset_name;
        $asset->category_id = $request->category_id;
        $asset->asset_description = $request->description;
        $asset->asset_serial = $request->asset_serial;
        $asset->purchase_date = $request->purchase_date;
        $asset->expiry_date = $request->expiry_date;
        $asset->status_id = $request->status_id;
        
        $asset->created_date = now();
        $asset->created_by = 1; // Default admin
        // assigned_to is 0 by default in DB usually, or null. Legacy uses 0.
        $asset->assigned_to = 0; 
        
        $asset->save();

        $this->logAction($asset->asset_id, 'Asset Created', 'Asset added to system');

        return redirect()->back()->with('success', 'Asset created successfully.');
    }

    public function show($id)
    {
        $asset = Asset::with(['category', 'assignee'])->findOrFail($id);
        $employees = Employee::where('is_deleted', 0)->where('is_hidden', 0)->orderBy('first_name')->get();
        
        // Logs are not related to Asset model in legacy directly via relationship usually, 
        // they are in system_logs with ref_type = 'asset' (implied) or similar.
        // Assuming generic SystemLog usage for now or a specific AssetLog if it existed (it didn't in Models).
        // Using SystemLog with some identifier. Legacy uses 'logs.php' which selects from `sys_logs` probably.
        // Assuming we log with a specific type.
        
        $logs = SystemLog::where('log_ref', $id)
            // ->where('log_type', 'asset') // If we had a type
            ->orderBy('log_date', 'desc')
            ->get();

        return view('admin.assets.show', compact('asset', 'employees', 'logs'));
    }

    public function assign(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:employees_list,employee_id',
            'remarks' => 'nullable|string'
        ]);

        $asset = Asset::findOrFail($id);
        $oldAssignee = $asset->assigned_to;
        
        $asset->assigned_to = $request->assigned_to;
        $asset->assigned_date = now();
        $asset->assigned_by = 1; // Auth::id() if possible
        $asset->save();

        $emp = Employee::find($request->assigned_to);
        $empName = $emp ? $emp->first_name . ' ' . $emp->last_name : 'Unknown';

        $this->logAction($id, 'Asset Assigned', "Assigned to $empName. " . $request->remarks);

        return redirect()->back()->with('success', 'Asset assigned successfully.');
    }

    private function logAction($refId, $action, $remark)
    {
        // Simple manual logging
        // Adjust table/fields as per actual SystemLog model if strictly defined
        /*
         Legacy SystemLog likely has: log_id, log_ref, log_date, log_action, log_remark, log_user_id...
        */
        $log = new SystemLog();
        $log->log_ref = $refId;
        $log->log_date = now();
        $log->log_action = $action;
        $log->log_remark = $remark;
        $log->log_user_id = 1; // Auth user
        // $log->log_type = 'asset'; // If column exists
        $log->save();
    }
}
