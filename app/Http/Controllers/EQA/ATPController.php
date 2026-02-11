<?php

namespace App\Http\Controllers\EQA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Atp;
use App\Models\AtpStatus;
use Illuminate\Support\Facades\DB;

class ATPController extends Controller
{
    public function index(Request $request)
    {
        $stt = $request->input('stt', '00');
        $query = Atp::with(['status', 'adder', 'emirate'])->orderBy('atp_id', 'desc');

        if ($stt != '00') {
            $query->where('atp_status_id', $stt);
        }

        $atps = $query->paginate(20);

        return view('eqa.atps.index', compact('atps'));
    }

    public function create()
    {
        // $statuses = AtpStatus::all(); 
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

        $atp = new Atp();
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
        $atp = Atp::with(['status', 'adder', 'emirate'])->findOrFail($id);
        $tab = $request->input('tab', 'planner'); // Default tab

        // Visit Planner Data (Info Requests)
        $infoRequests = DB::table('atps_info_request')
            ->leftJoin('employees_list', 'atps_info_request.added_by', '=', 'employees_list.employee_id')
            ->where('atp_id', $id)
            ->select('atps_info_request.*', 'employees_list.first_name as requester_first_name', 'employees_list.last_name as requester_last_name')
            ->orderBy('request_id', 'desc')
            ->get();

        // Full Audit Logs
        $logs = DB::table('atps_list_logs')
            ->leftJoin('employees_list', 'atps_list_logs.logged_by', '=', 'employees_list.employee_id')
            ->where('atp_id', $id)
            ->select('atps_list_logs.*', 'employees_list.first_name as logger_name', 'employees_list.last_name as logger_last_name')
            ->orderBy('log_id', 'desc')
            ->get();

        return view('eqa.atps.show', compact('atp', 'tab', 'infoRequests', 'logs'));
    }

    public function sendRegistrationEmail($id)
    {
        $atp = Atp::findOrFail($id);
        
        // Send the premium registration email
        $atp->notify(new \App\Notifications\AtpRegistrationNotification($atp));

        // Update status if needed (e.g. from New to Registration Sent)
        // Adjust the status_id according to your database (assuming 2 is 'Email Sent' or similar)
        $atp->status_id = 2; 
        $atp->save();

        return redirect()->back()->with('success', 'Premium registration email sent successfully to ' . $atp->atp_email);
    }

    public function accredit($id)
    {
        $atp = Atp::findOrFail($id);
        $atp->atp_status_id = 4; // Accredited (Mapping based on legacy logic)
        $atp->save();

        $this->logAtpAction($id, 'ATP Accredited');

        return redirect()->back()->with('success', 'Training Provider has been successfully accredited.');
    }

    // Visit Planner: Information Requests
    public function newInfoRequest($atp_id)
    {
        $atp = Atp::findOrFail($atp_id);
        return view('eqa.atps.requests.new', compact('atp'));
    }

    public function storeInfoRequest(Request $request, $atp_id)
    {
        $request->validate([
            'required_evidences' => 'required|array',
            'required_evidences.*' => 'required|string',
        ]);

        $requestId = DB::table('atps_info_request')->insertGetId([
            'atp_id' => $atp_id,
            'request_date' => now(),
            'added_by' => auth()->id() ?? 0,
            'request_status' => 'pending_submission',
            'request_department' => 2, // EQA
            'added_date' => now(),
        ]);

        foreach ($request->required_evidences as $evidence) {
            DB::table('atps_info_request_evs')->insert([
                'request_id' => $requestId,
                'required_evidence' => $evidence,
                'atp_id' => $atp_id,
                'request_department' => 2, // EQA
            ]);
        }
        
        $this->logAtpAction($atp_id, 'Created Information Request #' . $requestId);

        return redirect()->route('eqa.atps.show', ['id' => $atp_id, 'tab' => 'planner'])->with('success', 'Information Request created successfully.');
    }

    public function viewInfoRequest($atp_id, $request_id)
    {
        $atp = Atp::findOrFail($atp_id);
        
        $infoRequest = DB::table('atps_info_request')
            ->leftJoin('employees_list', 'atps_info_request.added_by', '=', 'employees_list.employee_id')
            ->where('atp_id', $atp_id)
            ->where('request_id', $request_id)
            ->select('atps_info_request.*', 'employees_list.first_name as requester_first_name', 'employees_list.last_name as requester_last_name')
            ->first();

        if (!$infoRequest) {
            abort(404, 'Information Request not found.');
        }

        $evidenceItems = DB::table('atps_info_request_evs')
            ->where('request_id', $request_id)
            ->get();

        return view('eqa.atps.requests.view', compact('atp', 'infoRequest', 'evidenceItems'));
    }

    private function logAtpAction($atp_id, $action)
    {
        DB::table('atps_list_logs')->insert([
            'atp_id' => $atp_id,
            'action' => $action,
            'logged_by' => auth()->id() ?? 0,
            'log_date' => now()
        ]);
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $stt = $request->input('stt', '00');
        $query = Atp::with(['status', 'adder', 'emirate'])->orderBy('atp_id', 'desc');

        // Status Filtering (Legacy Parity)
        if ($stt != '00') {
            $query->where('atp_status_id', $stt);
        }

        // Advanced Search (Legacy Parity)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('atp_name', 'like', "%{$search}%")
                  ->orWhere('atp_ref', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('atp_email', 'like', "%{$search}%")
                  ->orWhere('atp_phone', 'like', "%{$search}%");
            });
        }

        $atps = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $atps->items(),
            'pagination' => [
                'current_page' => $atps->currentPage(),
                'last_page' => $atps->lastPage(),
                'per_page' => $atps->perPage(),
                'total' => $atps->total(),
                'from' => $atps->firstItem(),
                'to' => $atps->lastItem(),
            ]
        ]);
    }
}
