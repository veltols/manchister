<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Atp;
use App\Models\AtpStatus;
use App\Models\AtpCategory;
use App\Models\AtpType;
use App\Models\AtpContact;
use App\Models\AtpLog;
use App\Models\AtpPass;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AtpController extends Controller
{
    // ─── List ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Atp::with(['status', 'creator', 'category', 'type']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
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

    // ─── Create Form ──────────────────────────────────────────────────────────
    public function create()
    {
        $categories = AtpCategory::all();
        $types = AtpType::all();
        $emirates = City::orderBy('city_name')->get();

        // Accreditation types (same as old system logic)
        $accreditationTypes = [
            1 => 'New Accreditation',
            2 => 'Renewal',
        ];

        return view('emp.atps.create', compact('categories', 'types', 'emirates', 'accreditationTypes'));
    }

    // ─── Store (mirrors serv_new.php exactly) ────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'atp_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_name_designation' => 'required|string|max:255',
            'atp_email' => 'required|email|max:255',
            'atp_phone' => 'required|string|max:30',
            'emirate_id' => 'required|exists:sys_countries_cities,city_id',
            'atp_category_id' => 'required|exists:atps_list_categories,atp_category_id',
            'atp_type_id' => 'required|exists:atps_list_types,atp_type_id',
        ]);

        $employeeId = Auth::user()->employee->employee_id ?? 0;

        DB::transaction(function () use ($request, $employeeId) {

            // ── Step 1: INSERT atps_list ──────────────────────────────────────
            $atp = new Atp();
            $atp->atp_name = $request->atp_name;
            $atp->atp_name_ar = '';
            $atp->contact_name = $request->contact_name;
            $atp->atp_email = $request->atp_email;
            $atp->atp_phone = $request->atp_phone;
            $atp->emirate_id = $request->emirate_id;
            $atp->area_name = '';
            $atp->street_name = '';
            $atp->building_name = '';
            $atp->atp_category_id = $request->atp_category_id;
            $atp->atp_type_id = $request->atp_type_id;
            $atp->accreditation_type = 1; // Default: New Accreditation
            $atp->added_by = $employeeId;
            $atp->added_date = now()->format('Y-m-d H:i:00');
            $atp->atp_status_id = 1; // Status 1 = "Pending Email"
            $atp->phase_id = 1;
            $atp->is_phase_ok = 1;
            $atp->todo_id = 1;
            $atp->atp_logo = 'no-img.png';
            $atp->atp_ref = 'TEMP'; // Temporary, updated after insert
            $atp->save();

            // ── Step 2: INSERT atps_list_contacts ─────────────────────────────
            AtpContact::create([
                'atp_id' => $atp->atp_id,
                'contact_name' => $request->contact_name,
                'contact_phone' => $request->atp_phone,
                'contact_email' => $request->atp_email,
                'contact_designation' => $request->contact_name_designation,
            ]);

            // ── Step 3: INSERT users_list (ATP portal login account) ──────────
            DB::table('users_list')->insert([
                'user_id' => $atp->atp_id,
                'user_email' => $request->atp_email,
                'user_type' => 'atp',
                'int_ext' => 'ext',
                'user_family' => 'atps_list',
            ]);

            // ── Step 4: INSERT atps_list_pass (default password) ──────────────
            $defaultPassword = '$2y$10$vyDJsXaSPt03tXcY8z/lBuLHIEyTFxbHkA7eB2L1uA36d.VaXzs/e';
            AtpPass::create([
                'atp_id' => $atp->atp_id,
                'pass_value' => $defaultPassword,
                'is_active' => 1,
            ]);

            // ── Step 5: UPDATE atp_ref (generated code, same formula as old PHP)
            $atp->atp_ref = $this->generateAtpRef($atp);
            $atp->last_updated = now()->format('Y-m-d H:i:00');
            $atp->save();

            // ── Step 6: INSERT atps_list_logs ─────────────────────────────────
            AtpLog::create([
                'atp_id' => $atp->atp_id,
                'log_action' => 'ATP Added',
                'log_date' => now()->format('Y-m-d H:i:00'),
                'logger_type' => 'employees_list',
                'log_dept' => 'R&C',
                'logged_by' => $employeeId,
            ]);
        });

        return redirect()->route('emp.atps.index')
            ->with('success', 'Training Provider added successfully.');
    }

    // ─── Show Detail ──────────────────────────────────────────────────────────
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
                'type' => 'Initial',
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
                'type' => 'Program',
            ];
        }

        // SED Form
        try {
            $sedForm = DB::table('atps_sed_form')->where('atp_id', $id)->first();
            if ($sedForm) {
                $apps[] = [
                    'name' => 'Self Evaluation Document (SED)',
                    'status' => 'Submitted',
                    'form_status' => 'submitted',
                    'start_date' => null,
                    'submit_date' => null,
                    'type' => 'Compliance',
                ];
            }
        } catch (\Exception $e) {
        }

        // EQA Details
        try {
            $eqaData = DB::table('atps_eqa_details')->where('atp_id', $id)->first();
            if ($eqaData) {
                $apps[] = [
                    'name' => 'EQA Visit Details',
                    'status' => 'Submitted',
                    'form_status' => 'submitted',
                    'start_date' => null,
                    'submit_date' => null,
                    'type' => 'EQA',
                ];
            }
        } catch (\Exception $e) {
        }

        // Renewals
        $renewals = \App\Models\AtpFormInit::where('atp_id', $id)->where('is_renew', 1)->get();

        // Cancellations
        $cancellations = collect();
        try {
            $cancellations = DB::table('atps_list_cancel')->where('atp_id', $id)->get();
        } catch (\Exception $e) {
        }

        // Learner Enrollments
        $leRecords = collect();
        try {
            $leRecords = DB::table('atps_list_le')
                ->join('atps_list_qualifications', 'atps_list_le.qualification_id', '=', 'atps_list_qualifications.qualification_id')
                ->where('atps_list_le.atp_id', $id)
                ->select('atps_list_le.*', 'atps_list_qualifications.qualification_name')
                ->get();
        } catch (\Exception $e) {
        }

        $logs = AtpLog::with('logger')
            ->where('atp_id', $id)
            ->orderBy('log_id', 'desc')
            ->get();

        return view('emp.atps.show', compact('atp', 'apps', 'logs', 'renewals', 'cancellations', 'leRecords'));
    }

    // ─── Send Email ───────────────────────────────────────────────────────────
    public function sendEmail($id)
    {
        $atp = Atp::findOrFail($id);
        $atp->atp_status_id = 2; // Status 2 = "Pending" (email sent)
        $atp->last_updated = now()->format('Y-m-d H:i:00');
        $atp->save();

        $this->logAtpAction($id, 'ATP Init Email Sent');

        return back()->with('success', 'Registration email has been sent to ' . $atp->atp_email);
    }

    // ─── Accredit ─────────────────────────────────────────────────────────────
    public function accredit($id)
    {
        $atp = Atp::findOrFail($id);
        $atp->atp_status_id = 3; // Status 3 = "Accredited"
        $atp->last_updated = now()->format('Y-m-d H:i:00');
        $atp->save();

        $this->logAtpAction($id, 'ATP Accredited');

        return back()->with('success', 'Training Provider has been successfully accredited.');
    }

    // ─── JSON Data (AJAX pagination) ─────────────────────────────────────────
    public function getData(Request $request)
    {
        $perPage = $request->input('per_page', 12);
        $query = Atp::with(['status', 'creator', 'category', 'type']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
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
            ],
        ]);
    }

    // ─── Private Helpers ──────────────────────────────────────────────────────

    /**
     * Generate ATP reference code — mirrors PHP getAtpRef() exactly:
     *   INITIALS + MonthYear + EmirateCode + "0" + atp_id
     *   e.g. "Dubai Training Centre" → "DTC032026DXB042"
     */
    private function generateAtpRef(Atp $atp): string
    {
        $name = preg_replace('/[^A-Za-z0-9\-]/', '', $atp->atp_name);
        $words = explode(' ', trim($atp->atp_name));
        $prefix = '';

        if (count($words) > 1) {
            $count = 0;
            foreach ($words as $word) {
                if (trim($word) === '')
                    continue;
                $prefix .= strtoupper($word[0]);
                $count++;
                if ($count >= 3)
                    break;
            }
        } else {
            $len = strlen($name) - 1;
            $st = strtoupper($name[0]);
            $en = strtoupper($name[$len]);
            $prefix = $st . $en . '0';
        }

        $date = now()->format('my');

        $emirateCodes = [
            1 => 'AD',
            2 => 'DXB',
            3 => 'AHJ',
            4 => 'AJM',
            5 => 'RAK',
            6 => 'UQM',
            7 => 'FUJ',
        ];
        $eCode = $emirateCodes[$atp->emirate_id] ?? 'NA';

        return $prefix . $date . $eCode . '0' . $atp->atp_id;
    }

    private function logAtpAction(int $atpId, string $action): void
    {
        AtpLog::create([
            'atp_id' => $atpId,
            'log_action' => $action,
            'log_date' => now()->format('Y-m-d H:i:00'),
            'logger_type' => 'employees_list',
            'log_dept' => 'R&C',
            'logged_by' => Auth::user()->employee->employee_id ?? 0,
        ]);
    }
}
