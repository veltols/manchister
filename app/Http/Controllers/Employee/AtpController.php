<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Atp;
use App\Models\AtpStatus;
use App\Models\AtpCategory;
use App\Models\AtpType;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AtpController extends Controller
{
    public function index(Request $request)
    {
        $query = Atp::with(['status', 'creator', 'category', 'type']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('atp_name', 'like', "%$s%")
                  ->orWhere('atp_ref', 'like', "%$s%")
                  ->orWhere('atp_email', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('atp_status_id', $request->status);
        } elseif ($request->filled('stt') && $request->stt != '00') {
            $query->where('atp_status_id', $request->stt);
        }

        $atps = $query->orderBy('atp_id', 'desc')->paginate(12);
        $statuses = AtpStatus::all();

        return view('emp.atps.index', compact('atps', 'statuses'));
    }

    public function create()
    {
        $categories = AtpCategory::all();
        $types = AtpType::all();
        $emirates = City::orderBy('city_name')->get();

        return view('emp.atps.create', compact('categories', 'types', 'emirates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'atp_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_name_designation' => 'required|string|max:255',
            'atp_email' => 'required|email|max:255',
            'atp_phone' => 'required|string|max:20',
            'emirate_id' => 'required|exists:sys_countries_cities,city_id',
            'atp_category_id' => 'required|exists:atps_list_categories,atp_category_id',
            'atp_type_id' => 'required|exists:atps_list_types,atp_type_id',
        ]);

        // Atps_list doesn't have contact_name_designation
        $atpData = $request->except(['_token', 'contact_name_designation']);
        
        $atp = new Atp($atpData);
        $atp->added_by = Auth::user()->employee->employee_id ?? 0;
        $atp->added_date = now();
        $atp->atp_status_id = 1; // Default status: Pending/Initial
        $atp->atp_ref = 'TEMP'; // Temporary value to satisfy non-null DB constraint
        $atp->save(); // Save first to get the ID

        // Create Contact Record
        \App\Models\AtpContact::create([
            'atp_id' => $atp->atp_id,
            'contact_name' => $request->contact_name,
            'contact_phone' => $request->atp_phone,
            'contact_email' => $request->atp_email,
            'contact_designation' => $request->contact_name_designation,
        ]);

        // Generate Legacy-equivalent Reference
        $atp->atp_ref = $this->generateAtpRef($atp);
        $atp->save();

        // Log the creation
        $this->logAtpAction($atp->atp_id, 'ATP Created');

        return redirect()->route('emp.atps.index')->with('success', 'Training Provider added successfully.');
    }

    private function generateAtpRef($atp)
    {
        $name = preg_replace('/[^A-Za-z0-9]/', '', $atp->atp_name);
        $words = explode(' ', $atp->atp_name);
        $prefix = '';
        if (count($words) > 1) {
            foreach (array_slice($words, 0, 3) as $w) {
                $prefix .= strtoupper($w[0]);
            }
        } else {
            $prefix = strtoupper(substr($name, 0, 1) . substr($name, -1) . '0');
        }
        
        $date = now()->format('my');
        $emirateCodes = [1 => 'AD', 2 => 'DXB', 3 => 'AHJ', 4 => 'AJM', 5 => 'RAK', 6 => 'UQM', 7 => 'FUJ'];
        $eCode = $emirateCodes[$atp->emirate_id] ?? 'NA';
        
        return $prefix . $date . $eCode . '0' . $atp->atp_id;
    }

    private function logAtpAction($atpId, $action)
    {
        \App\Models\AtpLog::create([
            'atp_id' => $atpId,
            'log_action' => $action,
            'log_date' => now(),
            'logger_type' => 'employees_list',
            'log_dept' => 'R&C',
            'logged_by' => Auth::user()->employee->employee_id ?? 0
        ]);
    }

    public function sendEmail($id)
    {
        $atp = Atp::findOrFail($id);
        $atp->atp_status_id = 2; // Email Sent / Pending User Input
        $atp->save();

        $this->logAtpAction($id, 'ATP Init Email Sent');

        return back()->with('success', 'Registration email has been sent to ' . $atp->atp_email);
    }

    public function accredit($id)
    {
        $atp = Atp::findOrFail($id);
        $atp->atp_status_id = 4; // Accredited (Mapping based on legacy logic)
        $atp->save();

        $this->logAtpAction($id, 'ATP Accredited');

        return back()->with('success', 'Training Provider has been successfully accredited.');
    }

    public function show($id)
    {
        $atp = Atp::with(['status', 'category', 'type', 'creator', 'emirate', 'contacts'])->findOrFail($id);
        
        $apps = [];
        
        // Initial Registration
        $initForm = \App\Models\AtpFormInit::where('atp_id', $id)->first();
        if ($initForm) {
            $apps[] = [
                'name' => 'Initial Registration Form',
                'status' => $initForm->is_submitted ? 'Submitted' : 'Pending',
                'form_status' => $initForm->form_status,
                'start_date' => $initForm->added_date,
                'submit_date' => $initForm->submitted_date,
                'type' => 'Initial'
            ];
        }

        // Program Registration Request
        $regReq = \App\Models\AtpProgRegisterReq::where('atp_id', $id)->first();
        if ($regReq) {
            $apps[] = [
                'name' => 'Information Request Form',
                'status' => $regReq->is_submitted ? 'Submitted' : 'Pending',
                'form_status' => $regReq->form_status,
                'start_date' => $regReq->added_date,
                'submit_date' => $regReq->submitted_date,
                'type' => 'Program'
            ];
        }

        // SED Form (Check if table exists and fetch)
        try {
            $sedForm = \DB::table('atps_sed_form')->where('atp_id', $id)->first();
            if ($sedForm) {
                $apps[] = [
                    'name' => 'Self Evaluation Document (SED)',
                    'status' => $sedForm->is_submitted ? 'Submitted' : 'Pending',
                    'form_status' => $sedForm->form_status ?? 'N/A',
                    'start_date' => $sedForm->added_date ?? null,
                    'submit_date' => $sedForm->submitted_date ?? null,
                    'type' => 'Compliance'
                ];
            }
        } catch (\Exception $e) {}

        // EQA Details
        try {
            $eqaData = \DB::table('atps_eqa_details')->where('atp_id', $id)->first();
            if ($eqaData) {
                $apps[] = [
                    'name' => 'EQA Visit Details',
                    'status' => $eqaData->is_submitted ? 'Submitted' : 'Pending',
                    'form_status' => $eqaData->form_status ?? 'N/A',
                    'start_date' => $eqaData->added_date ?? null,
                    'submit_date' => $eqaData->submitted_date ?? null,
                    'type' => 'EQA'
                ];
            }
        } catch (\Exception $e) {}

        // Renewals
        $renewals = \App\Models\AtpFormInit::where('atp_id', $id)->where('is_renew', 1)->get();

        // Cancellations
        $cancellations = [];
        try {
            $cancellations = \DB::table('atps_list_cancel')->where('atp_id', $id)->get();
        } catch (\Exception $e) {}

        // Learner Enrollments
        $leRecords = [];
        try {
            $leRecords = \DB::table('atps_list_le')
                ->join('atps_list_qualifications', 'atps_list_le.qualification_id', '=', 'atps_list_qualifications.qualification_id')
                ->where('atps_list_le.atp_id', $id)
                ->select('atps_list_le.*', 'atps_list_qualifications.qualification_name')
                ->get();
        } catch (\Exception $e) {}

        $logs = \App\Models\AtpLog::with('logger')
            ->where('atp_id', $id)
            ->where('log_dept', 'R&C')
            ->orderBy('log_id', 'desc')
            ->get();

        return view('emp.atps.show', compact('atp', 'apps', 'logs', 'renewals', 'cancellations', 'leRecords'));
    }

    public function getData(Request $request)
    {
        $perPage = $request->input('per_page', 12);
        $query = Atp::with(['status', 'creator', 'category', 'type']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('atp_name', 'like', "%$s%")
                  ->orWhere('atp_ref', 'like', "%$s%")
                  ->orWhere('atp_email', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('atp_status_id', $request->status);
        } elseif ($request->filled('stt') && $request->stt != '00') {
            $query->where('atp_status_id', $request->stt);
        }

        $atps = $query->orderBy('atp_id', 'desc')->paginate($perPage);

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
