<?php

namespace App\Http\Controllers\EQA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ATP;
use App\Models\ATPStatus;
use Illuminate\Support\Facades\DB;

class ATPController extends Controller
{
    public function index(Request $request)
    {
        $viewType = $request->input('view', 'list');
        $query = ATP::with(['status', 'adder'])->orderBy('atp_id', 'desc');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('atp_name', 'like', "%{$search}%")
                ->orWhere('atp_ref', 'like', "%{$search}%");
        }

        $atps = $query->paginate(20);

        return view('eqa.atps.index', compact('atps', 'viewType'));
    }

    public function create()
    {
        // $statuses = ATPStatus::all(); 
        return view('eqa.atps.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'atp_name' => 'required|string',
            'contact_name' => 'required|string',
            'atp_email' => 'required|email',
            'atp_phone' => 'required|string'
        ]);

        $atp = new ATP();
        $atp->atp_ref = 'ATP-' . strtoupper(uniqid());
        $atp->atp_name = $request->atp_name;
        $atp->contact_name = $request->contact_name;
        $atp->atp_email = $request->atp_email;
        $atp->atp_phone = $request->atp_phone;
        $atp->status_id = 1; // Default status (e.g. New)
        $atp->added_by = auth()->id() ?? 0;
        $atp->added_date = now();
        $atp->atp_emirate = 'AD'; // Default per legacy
        $atp->save();

        return redirect()->route('eqa.atps.index')->with('success', 'Training Provider Added');
    }

    public function show($id, Request $request)
    {
        $atp = ATP::with(['status', 'adder'])->findOrFail($id);
        $tab = $request->input('tab', 'planner'); // Default tab

        return view('eqa.atps.show', compact('atp', 'tab'));
    }

    public function sendRegistrationEmail($id)
    {
        $atp = ATP::findOrFail($id);
        // Logic to send email
        $atp->status_id = 2;
        $atp->save();

        return redirect()->back()->with('success', 'Registration email sent successfully.');
    }
}
