<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Communication;
use App\Models\CommunicationStatus;
use App\Models\CommunicationType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CommunicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Communication::with(['status', 'type', 'requester'])->orderBy('communication_id', 'desc');

        if ($request->has('type_id') && $request->type_id != '') {
            $query->where('communication_type_id', $request->type_id);
        }

        $records = $query->paginate(15);
        $types = CommunicationType::all();
        $statuses = CommunicationStatus::all();

        return view('hr.communications.index', compact('records', 'types', 'statuses'));
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $query = Communication::with(['status', 'type', 'requester'])->orderBy('communication_id', 'desc');

        if ($request->has('type_id') && $request->type_id != '') {
            $query->where('communication_type_id', $request->type_id);
        }

        $records = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $records->items(),
            'pagination' => [
                'current_page' => $records->currentPage(),
                'last_page' => $records->lastPage(),
                'per_page' => $records->perPage(),
                'total' => $records->total(),
                'from' => $records->firstItem(),
                'to' => $records->lastItem(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'external_party_name' => 'required|string|max:255',
            'communication_subject' => 'required|string|max:255',
            'communication_type_id' => 'required|exists:m_communications_list_types,communication_type_id',
        ]);

        $comm = new Communication();
        $comm->communication_code = 'COM-' . strtoupper(Str::random(6)); // Auto-gen code
        $comm->external_party_name = $request->external_party_name;
        $comm->communication_subject = $request->communication_subject;
        $comm->communication_description = $request->communication_description ?? '';
        $comm->information_shared = $request->information_shared ?? '';
        $comm->communication_type_id = $request->communication_type_id;
        $comm->requested_by = Auth::user()->employee_id ?? Auth::id(); // Use employee ID if available
        $comm->requested_date = now();
        
        // Default status? Legacy logic might set it. Assuming 1 (New/Pending)
        $comm->communication_status_id = 1;

        $comm->save();

        return redirect()->back()->with('success', 'Communication record created.');
    }
}
