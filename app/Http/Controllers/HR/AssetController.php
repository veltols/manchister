<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Employee;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::with(['category', 'assignee'])
            ->orderBy('asset_id', 'desc')
            ->paginate(15);
            
        $employees = Employee::where('is_deleted', 0)->where('is_hidden', 0)->orderBy('first_name')->get();
        $categories = AssetCategory::orderBy('category_name')->get();

        return view('hr.assets.index', compact('assets', 'employees', 'categories'));
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $assets = Asset::with(['category', 'assignee'])
            ->orderBy('asset_id', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $assets->items(),
            'pagination' => [
                'current_page' => $assets->currentPage(),
                'last_page' => $assets->lastPage(),
                'per_page' => $assets->perPage(),
                'total' => $assets->total(),
                'from' => $assets->firstItem(),
                'to' => $assets->lastItem(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_name' => 'required|string|max:255',
            'category_id' => 'required|exists:z_assets_list_cats,category_id',
            'asset_ref' => 'required|string|max:50',
            'asset_serial' => 'nullable|string',
        ]);

        $asset = new Asset();
        $asset->asset_name = $request->asset_name;
        $asset->category_id = $request->category_id;
        $asset->asset_ref = $request->asset_ref;
        $asset->asset_serial = $request->asset_serial;
        $asset->asset_description = $request->asset_description;
        $asset->assigned_to = 0; // Default unassigned
        $asset->save();

        return redirect()->back()->with('success', 'Asset added successfully.');
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        // Simple assignment update logic combined here contextually or separate method
        if($request->has('assigned_to')){
             $asset->assigned_to = $request->assigned_to;
             $asset->assigned_date = now();
             $asset->save();
             return redirect()->back()->with('success', 'Asset assigned successfully.');
        }

        return redirect()->back();
    }
}
