<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Atp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtpFormController extends Controller
{
    public function showRegistrationRequest($atpId)
    {
        $atp = Atp::findOrFail($atpId);
        
        $regReq = DB::table('atps_register_request')->where('atp_id', $atpId)->first();
        $locations = DB::table('atps_list_locations')->where('atp_id', $atpId)->get();
        $qualifications = DB::table('atps_list_qualifications')->where('atp_id', $atpId)->get();
        
        $learnerEnrolled = DB::table('atps_learner_enrolled')->where('atp_id', $atpId)->first();
        
        // Faculty: Types mapping based on legacy findName logic
        // 1 = IQA, 3 = Assessor, 4/5 = Trainer
        $trainers = DB::table('atps_list_faculties')->where('atp_id', $atpId)->whereIn('faculty_type', [4, 5])->get();
        $assessors = DB::table('atps_list_faculties')->where('atp_id', $atpId)->where('faculty_type', 3)->get();
        $iqas = DB::table('atps_list_faculties')->where('atp_id', $atpId)->where('faculty_type', 1)->get();

        return view('emp.atps.forms.registration_request', compact(
            'atp', 'regReq', 'locations', 'qualifications', 'learnerEnrolled', 'trainers', 'assessors', 'iqas'
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
}
