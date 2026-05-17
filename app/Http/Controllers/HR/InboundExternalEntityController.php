<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InboundExternalEntity;
use Illuminate\Support\Facades\Validator;

class InboundExternalEntityController extends Controller
{
    public function index()
    {
        $emirates = \App\Models\City::where('country_id', 224)->orderBy('city_name')->get();
        $categories = \App\Models\SysList::where('item_category', 'entity_category')->orderBy('item_name')->get();
        $types = \App\Models\SysList::where('item_category', 'entity_type')->orderBy('item_name')->get();

        return view('hr.external_entities.index', compact('emirates', 'categories', 'types'));
    }

    public function getData(Request $request)
    {
        $query = InboundExternalEntity::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('entity_name', 'like', "%{$search}%")
                  ->orWhere('entity_code', 'like', "%{$search}%")
                  ->orWhere('entity_email', 'like', "%{$search}%");
            });
        }

        $entities = $query->orderBy('entity_name', 'asc')->paginate(10);
        return response()->json($entities);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entity_name' => 'required|string|max:255',
            'entity_code' => 'required|string|max:100|unique:inbound_external_entities',
            'entity_email' => 'nullable|email|max:255',
            'entity_phone' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'emirate_id' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'type_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Validation failed.');
        }

        InboundExternalEntity::create([
            'entity_name' => $request->entity_name,
            'entity_code' => $request->entity_code,
            'entity_email' => $request->entity_email,
            'entity_phone' => $request->entity_phone,
            'contact_person' => $request->contact_person,
            'emirate_id' => $request->emirate_id,
            'category_id' => $request->category_id,
            'type_id' => $request->type_id,
            'is_active' => 1,
        ]);

        return redirect()->back()->with('success', 'External Entity created successfully.');
    }

    public function update(Request $request, $id)
    {
        $entity = InboundExternalEntity::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'entity_name' => 'required|string|max:255',
            'entity_code' => 'required|string|max:100|unique:inbound_external_entities,entity_code,' . $id . ',entity_id',
            'entity_email' => 'nullable|email|max:255',
            'entity_phone' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'emirate_id' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'type_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Validation failed.');
        }

        $entity->update([
            'entity_name' => $request->entity_name,
            'entity_code' => $request->entity_code,
            'entity_email' => $request->entity_email,
            'entity_phone' => $request->entity_phone,
            'contact_person' => $request->contact_person,
            'emirate_id' => $request->emirate_id,
            'category_id' => $request->category_id,
            'type_id' => $request->type_id,
            'is_active' => 1,
        ]);

        return redirect()->back()->with('success', 'External Entity updated successfully.');
    }

    public function destroy($id)
    {
        $entity = InboundExternalEntity::findOrFail($id);
        $entity->delete();

        return redirect()->back()->with('success', 'External Entity deleted successfully.');
    }
}
