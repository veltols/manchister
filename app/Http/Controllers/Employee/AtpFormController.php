<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Atp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtpFormController extends Controller
{
    public function showInitialForm($atpId)
    {
        $atp = Atp::with(['emirate', 'category'])->findOrFail($atpId);
        $initForm = DB::table('atps_form_init')->where('atp_id', $atpId)->first();

        $contacts = DB::table('atps_list_contacts')->where('atp_id', $atpId)->get();
        $qualifications = DB::table('atps_list_qualifications')->where('atp_id', $atpId)->get();
        $faculty = DB::table('atps_list_faculties')
            ->leftJoin('atps_list_faculties_types', 'atps_list_faculties.faculty_type_id', '=', 'atps_list_faculties_types.faculty_type_id')
            ->where('atp_id', $atpId)
            ->get();

        $learnerStatsReg = DB::table('atps_learners_statistics')
            ->leftJoin('atps_list_qualifications', 'atps_learners_statistics.qualification_id', '=', 'atps_list_qualifications.qualification_id')
            ->where('atps_learners_statistics.atp_id', $atpId)
            ->where('statistic_type', 'registered')
            ->get();

        $learnerStatsAwarded = DB::table('atps_learners_statistics')
            ->leftJoin('atps_list_qualifications', 'atps_learners_statistics.qualification_id', '=', 'atps_list_qualifications.qualification_id')
            ->where('atps_learners_statistics.atp_id', $atpId)
            ->where('statistic_type', 'awarded')
            ->get();

        $electronicSystems = DB::table('atps_electronic_systems')->where('atp_id', $atpId)->get();

        return view('emp.atps.forms.initial_form', compact(
            'atp',
            'initForm',
            'contacts',
            'qualifications',
            'faculty',
            'learnerStatsReg',
            'learnerStatsAwarded',
            'electronicSystems'
        ));
    }

    public function updateStatus(Request $request, $atpId)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,review',
            'rc_comment' => 'nullable|string',
            'form_type' => 'required|in:initial,program,sed'
        ]);

        try {
            $atp = Atp::findOrFail($atpId);
            $table = 'atps_form_init';
            if ($request->form_type == 'program') {
                $table = 'atps_prog_register_req';
            } elseif ($request->form_type == 'sed') {
                $table = 'atps_sed_form';
            }

            DB::table($table)
                ->where('atp_id', $atpId)
                ->update([
                    'form_status' => $request->status,
                    'rc_comment' => $request->rc_comment,
                    'eqa_user_id' => $request->eqa_user_id ?? null
                ]);

            // Update ATP Phase according to legacy logic
            $phase_id = $atp->phase_id;
            $is_phase_ok = 0;

            if ($request->status == 'approved') {
                if ($request->form_type == 'initial') {
                    $phase_id = 2;
                    $is_phase_ok = 1;
                } elseif ($request->form_type == 'program') {
                    if ($atp->accreditation_type == 1) {
                        $phase_id = 3;
                    } else {
                        $phase_id = 6;
                    }
                    $is_phase_ok = 1;

                    // If EQA user is assigned, create/update EQA detail record
                    if ($request->eqa_user_id) {
                        DB::table('atps_eqa_details')->updateOrInsert(
                            ['atp_id' => $atpId],
                            [
                                'assigned_to' => $request->eqa_user_id,
                                'form_status' => 'pending',
                                'added_date' => now(),
                                'eqa_visit_date' => null
                            ]
                        );
                    }
                } elseif ($request->form_type == 'sed') {
                    $phase_id = 5;
                    $is_phase_ok = 0;
                }
            } else {
                // Rejected or Review
                if ($request->form_type == 'initial') {
                    $phase_id = 1;
                } elseif ($request->form_type == 'program') {
                    $phase_id = 2;
                } elseif ($request->form_type == 'sed') {
                    $phase_id = 4;
                }
                $is_phase_ok = 0;
            }

            $atp->update([
                'phase_id' => $phase_id,
                'is_phase_ok' => $is_phase_ok
            ]);

            // Log the action
            $formNames = [
                'initial' => 'Initial Form',
                'program' => 'Program Registration',
                'sed' => 'SED Form'
            ];
            $formName = $formNames[$request->form_type] ?? "ATP Form";
            $action = "IQC " . (($request->status == 'approved') ? "Approved" : (($request->status == 'rejected') ? "Denied" : "Commented on")) . " $formName";

            \App\Models\AtpLog::create([
                'atp_id' => $atpId,
                'log_action' => $action,
                'log_date' => now()->toDateTimeString(),
                'logger_type' => 'employees_list',
                'log_dept' => 'R&C',
                'logged_by' => auth()->user()->user_id ?? 0
            ]);

            return response()->json(['success' => true, 'message' => 'Status and Phase updated successfully']);
        } catch (\Exception $e) {
            \Log::error("ATP Status Update Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function showRegistrationRequest($atpId)
    {
        $atp = Atp::findOrFail($atpId);

        $regReq = DB::table('atps_prog_register_req')->where('atp_id', $atpId)->first();
        $locations = DB::table('atps_list_locations')->where('atp_id', $atpId)->get();
        $qualifications = DB::table('atps_list_qualifications')->where('atp_id', $atpId)->get();

        // Load mappings for qualifications
        $mappings = DB::table('atps_list_qualifications_faculties')
            ->join('atps_list_faculties', 'atps_list_qualifications_faculties.faculty_id', '=', 'atps_list_faculties.faculty_id')
            ->where('atps_list_qualifications_faculties.atp_id', $atpId)
            ->select('atps_list_qualifications_faculties.qualification_id', 'atps_list_faculties.faculty_name')
            ->get()
            ->groupBy('qualification_id');

        foreach ($qualifications as $q) {
            $q->mapped_faculty = isset($mappings[$q->qualification_id]) ? $mappings[$q->qualification_id]->pluck('faculty_name')->toArray() : [];
        }

        $learnerEnrolled = DB::table('atps_learner_enrolled')->where('atp_id', $atpId)->first();

        // Faculty: Types mapping based on legacy findName logic
        // 1 = IQA, 3 = Assessor, 4/5 = Trainer
        $trainers = DB::table('atps_list_faculties')->where('atp_id', $atpId)->whereIn('faculty_type_id', [4, 5])->get();
        $assessors = DB::table('atps_list_faculties')->where('atp_id', $atpId)->where('faculty_type_id', 3)->get();
        $iqas = DB::table('atps_list_faculties')->where('atp_id', $atpId)->where('faculty_type_id', 1)->get();

        $electronicSystems = DB::table('atps_electronic_systems')->where('atp_id', $atpId)->get();

        return view('emp.atps.forms.registration_request', compact(
            'atp',
            'regReq',
            'locations',
            'qualifications',
            'learnerEnrolled',
            'trainers',
            'assessors',
            'iqas',
            'electronicSystems'
        ));
    }

    public function showSed($atpId)
    {
        $atp = Atp::findOrFail($atpId);
        $sedForm = DB::table('atps_sed_form')->where('atp_id', $atpId)->first();
        $qualifications = DB::table('atps_list_qualifications')->where('atp_id', $atpId)->get();

        // Standard summary scores (Legacy AvgScore logic)
        $mains = DB::table('quality_standards_mains')->orderBy('main_id', 'asc')->get();
        $standardsData = [];
        $totalPoints = 0;

        foreach ($mains as $main) {
            $mainId = $main->main_id;

            // Total questions in this standard
            $mainTotal = DB::table('quality_standards')
                ->join('quality_standards_cats', 'quality_standards.qs_id', '=', 'quality_standards_cats.qs_id')
                ->where('quality_standards.main_id', $mainId)
                ->count();

            // Answered questions
            $totFilled = DB::table('atp_compliance')
                ->where('atp_id', $atpId)
                ->where('main_id', $mainId)
                ->count();

            // Answered correctly (answer = 1)
            $totOk = DB::table('atp_compliance')
                ->where('atp_id', $atpId)
                ->where('main_id', $mainId)
                ->where('answer', 1)
                ->count();

            $score = ($totFilled > 0) ? ceil(($totOk / $totFilled) * 100) : 0;
            $totalPoints += $score;

            $standardsData[] = [
                'main_id' => $mainId,
                'title' => $main->main_title,
                'icon' => $main->main_icon,
                'score' => $score
            ];
        }

        $avgScore = (count($mains) > 0) ? ceil($totalPoints / count($mains)) : 0;

        // Quality Improvement Plan (answer = 3)
        $qipItems = DB::table('atp_compliance')
            ->join('quality_standards_cats', 'atp_compliance.cat_id', '=', 'quality_standards_cats.cat_id')
            ->where('atp_compliance.atp_id', $atpId)
            ->where('atp_compliance.answer', 3)
            ->select('atp_compliance.*', 'quality_standards_cats.cat_ref', 'quality_standards_cats.cat_description')
            ->get();

        return view('emp.atps.forms.sed', compact('atp', 'sedForm', 'qualifications', 'standardsData', 'avgScore', 'qipItems'));
    }

    public function showCompliance($atpId, $mainId)
    {
        $atp = Atp::findOrFail($atpId);
        $standard = DB::table('quality_standards_mains')->where('main_id', $mainId)->first();

        $complianceRecords = DB::table('atp_compliance')
            ->join('quality_standards_cats', 'atp_compliance.cat_id', '=', 'quality_standards_cats.cat_id')
            ->where('atp_compliance.atp_id', $atpId)
            ->where('atp_compliance.main_id', $mainId)
            ->select('atp_compliance.*', 'quality_standards_cats.cat_ref', 'quality_standards_cats.cat_description')
            ->get();

        return view('emp.atps.forms.compliance', compact('atp', 'standard', 'complianceRecords'));
    }

    public function showFaculty($atpId)
    {
        $atp = Atp::findOrFail($atpId);
        $faculty = DB::table('atps_list_faculties')->where('atp_id', $atpId)->get();

        return view('emp.atps.forms.faculty', compact('atp', 'faculty'));
    }
    public function getEqaUsers()
    {
        try {
            $users = DB::table('users_list')
                ->join('employees_list', 'users_list.user_id', '=', 'employees_list.employee_id')
                ->whereRaw('LOWER(users_list.user_type) = ?', ['eqa'])
                ->select('employees_list.employee_id', 'employees_list.first_name', 'employees_list.last_name')
                ->get();

            \Log::info('EQA Users Fetched: ' . $users->count());

            return response()->json(['success' => true, 'data' => $users]);
        } catch (\Exception $e) {
            \Log::error('Error fetching EQA users: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
