<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetStatus;
use App\Models\Employee;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');

        $query = Asset::with(['category', 'status', 'assignedTo']);

        if ($status == 'expired') {
            $query->where('expiry_date', '<', now());
        } elseif ($status == 'expiring') {
            // About to expire logic (e.g., next 30 days)
            $query->whereBetween('expiry_date', [now(), now()->addDays(30)]);
        }

        $assets = $query->orderBy('asset_id', 'desc')->paginate(20);

        return view('admin.assets.index', compact('assets', 'status'));
    }

    public function create()
    {
        $categories = AssetCategory::orderBy('category_name')->get();
        $statuses = AssetStatus::orderBy('status_name')->get();
        return view('admin.assets.create', compact('categories', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_name' => 'required|string',
            'category_id' => 'required|exists:z_assets_list_cats,category_id',
            'status_id' => 'required|exists:z_assets_list_status,status_id',
            // 'asset_serial' => 'required|string|unique:z_assets_list,asset_serial', // Can be duplicate?
        ]);

        $asset = new Asset();
        $asset->asset_ref = 'AST-' . strtoupper(uniqid()); // Auto-gen REF
        $asset->asset_name = $request->asset_name;
        $asset->asset_description = $request->asset_description ?? '';
        $asset->asset_serial = $request->asset_serial ?? '';
        $asset->purchase_date = $request->purchase_date;
        $asset->expiry_date = $request->expiry_date;
        $asset->category_id = $request->category_id;
        $asset->status_id = $request->status_id;
        $asset->created_by = auth()->id() ?? 0;
        $asset->created_at = now();
        $asset->save();

        return redirect()->route('admin.assets.index')->with('success', 'Asset created successfully.');
    }

    public function reassign(Request $request, $id)
    {
        // Logic to assign asset to user
    }
}
