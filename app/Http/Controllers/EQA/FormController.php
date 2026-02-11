<?php

namespace App\Http\Controllers\EQA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Atp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FormController extends Controller
{
    // Generic method to load a form
    public function show($form_id, $atp_id)
    {
        $atp = Atp::findOrFail($atp_id);

        // Map form_id to view
        $viewMap = [
            '003' => 'eqa.forms.003', // Accreditation Report
            '004' => 'eqa.forms.004', // Internal Report
            '006' => 'eqa.forms.006', // Feedback
            '007' => 'eqa.forms.007', // Evidence Log
            '008' => 'eqa.forms.008', // Visit Planner
            '014' => 'eqa.forms.014', // Site Inspection
            '017' => 'eqa.forms.017', // Assessor Interview
            '018' => 'eqa.forms.018', // IQA Interview
            '019' => 'eqa.forms.019', // Learner Interview
            '020' => 'eqa.forms.020', // Lead IQA Interview
            '028' => 'eqa.forms.028', // Live Assessment
        ];

        if (!array_key_exists($form_id, $viewMap)) {
            abort(404, 'Form not found.');
        }

        // Consolidated Data Loading
        $formData = null;
        if (in_array($form_id, ['008', '003'])) {
            $formData = DB::table('atps_eqa_details')->where('atp_id', $atp_id)->first();
        } elseif (in_array($form_id, ['004', '014'])) {
            // Checklists fallback
            $tableName = 'eqa_' . $form_id;
            if (Schema::hasTable($tableName)) {
                $formData = DB::table($tableName)->where('atp_id', $atp_id)->first();
            }
        } else {
            // Fallback for others if they have specific tables
            $tableName = 'eqa_' . $form_id;
            if (Schema::hasTable($tableName)) {
                $formData = DB::table($tableName)->where('atp_id', $atp_id)->first();
            }
        }

        // Ensure formData is an object to avoid "property of non-object" or "collection instance" errors
        if (!$formData) {
            $formData = (object) [];
        }

        // Context Data
        $qualifications = [];
        $faculties = [];
        $sed_data = null;

        if ($form_id == '008') {
            $qualifications = DB::table('atps_list_qualifications')->where('atp_id', $atp_id)->get();
            $faculties = DB::table('atps_list_faculties')->where('atp_id', $atp_id)->get();
            $sed_data = DB::table('atps_sed_form')->where('atp_id', $atp_id)->first();
        }

        if ($form_id == '004') {
            $sed_data = DB::table('atps_sed_form')->where('atp_id', $atp_id)->first();
        }

        if ($form_id == '006') {
            $sed_data = DB::table('atps_sed_form')->where('atp_id', $atp_id)->first();
            
            // Compliance/Evidence Data
            // Compliance/Evidence Data
            if (Schema::hasTable('quality_standards_cats')) {
                $complianceData = DB::table('atp_compliance')
                    ->join('quality_standards_cats', 'atp_compliance.cat_id', '=', 'quality_standards_cats.cat_id')
                    ->where('atp_compliance.atp_id', $atp_id)
                    ->select('atp_compliance.*', 'quality_standards_cats.cat_ref', 'quality_standards_cats.cat_description')
                    ->get();
            } else {
                // Fallback if table doesn't exist
                $complianceData = DB::table('atp_compliance')
                    ->where('atp_id', $atp_id)
                    ->get()
                    ->map(function($item) {
                        $item->cat_ref = 'N/A';
                        $item->cat_description = 'Description unavailable (Missing Table)';
                        return $item;
                    });
            }

            // Quality Standards Mains for Summary
            if (Schema::hasTable('quality_standards_mains')) {
                $qsMains = DB::table('quality_standards_mains')->orderBy('main_id')->get();
            } elseif (Schema::hasTable('quality_standard_main')) {
                 $qsMains = DB::table('quality_standard_main')->orderBy('main_id')->get();
            } else {
                $qsMains = collect([]);
            }
            
            return view($viewMap[$form_id], compact('atp', 'formData', 'form_id', 'sed_data', 'complianceData', 'qsMains'));
        }

        if ($form_id == '007') {
            $sed_data = DB::table('atps_sed_form')->where('atp_id', $atp_id)->first();
            
            $areas = [];
            if (Schema::hasTable('eqa_007_areas')) {
                $areas = DB::table('eqa_007_areas')->where('atp_id', $atp_id)->get();
            }

            $interviews = collect([]);
            if (Schema::hasTable('eqa_007_interview')) {
                $interviews = DB::table('eqa_007_interview')->where('atp_id', $atp_id)->get();
            }

            $staffInterviews = $interviews->where('interview_type', 'staff');
            $iqaInterviews = $interviews->where('interview_type', 'iqa');
            $trainInterviews = $interviews->where('interview_type', 'train');

            return view($viewMap[$form_id], compact('atp', 'formData', 'form_id', 'sed_data', 'areas', 'staffInterviews', 'iqaInterviews', 'trainInterviews'));
        }

        return view($viewMap[$form_id], compact('atp', 'formData', 'form_id', 'qualifications', 'faculties', 'sed_data'));
    }

    public function store($form_id, $atp_id, Request $request)
    {
        $tableName = 'eqa_' . $form_id;

        // Custom Table Mapping for Consolidated Data
        if (in_array($form_id, ['008', '003'])) {
            $tableName = 'atps_eqa_details';
        }

        $data = $request->except(['_token']);
        $data['atp_id'] = $atp_id;

        // Special handling for checklists (004, 014)
        if (in_array($form_id, ['004', '014'])) {
            // This normally involves a loop of multiple items
            // For now, if it's a generic save, we handle it.
            // If the request contains arrays, we need to iterate.
        }

        if (Schema::hasTable($tableName)) {
            DB::table($tableName)->updateOrInsert(
                ['atp_id' => $atp_id],
                $data
            );
        }

        return redirect()->back()->with('success', 'Form saved successfully.');
    }

    public function save006(Request $request)
    {
        $request->validate([
            'record_id' => 'required|integer',
            'atp_id' => 'required|integer',
            'eqa_criteria' => 'required',
            'eqa_feedback' => 'nullable|string',
        ]);

        $updated = DB::table('atp_compliance')
            ->where('record_id', $request->record_id)
            ->where('atp_id', $request->atp_id)
            ->update([
                'eqa_criteria' => $request->eqa_criteria,
                'eqa_feedback' => $request->eqa_feedback,
                // 'updated_at' => now() // If column exists
            ]);

        if ($updated) {
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Record not found or no changes made.']);
    }

    public function save007(Request $request)
    {
        $atp_id = $request->atp_id;

        // 1. Areas to Review
        if ($request->has('areas')) {
            $areas = $request->areas; // IDs
            $a1s = $request->a1s ?? [];
            $a2s = $request->a2s ?? [];
            $a3s = $request->a3s ?? [];

            foreach ($areas as $index => $id) {
                if (!isset($a1s[$index])) continue; // Skip empty/malformed

                $data = [
                    'atp_id' => $atp_id,
                    'a1' => $a1s[$index],
                    'a2' => $a2s[$index] ?? '',
                    'a3' => $a3s[$index] ?? '',
                ];

                if ($id > 0) {
                    if (Schema::hasTable('eqa_007_areas')) {
                        DB::table('eqa_007_areas')->where('record_id', $id)->update($data);
                    }
                } else {
                    $data['added_by'] = auth()->id() ?? 1;
                    $data['added_date'] = now();
                    if (Schema::hasTable('eqa_007_areas')) {
                        DB::table('eqa_007_areas')->insert($data);
                    }
                }
            }
        }

        // 2. Interviews (Shared Table: eqa_007_interview)
        // Staff
        if ($request->has('staff_names')) {
            $ids = $request->interview_ids ?? [];
            $names = $request->staff_names;
            $roles = $request->staff_roles ?? [];
            $comments = $request->eqa_comments ?? [];

            foreach ($names as $index => $name) {
                $id = $ids[$index] ?? 0;
                $data = [
                    'atp_id' => $atp_id,
                    'interview_type' => 'staff',
                    'staff_name' => $name,
                    'staff_role' => $roles[$index] ?? '',
                    'eqa_comment' => $comments[$index] ?? '',
                ];

                $this->saveInterview($id, $data);
            }
        }

        // IQA
        if ($request->has('iqa_questions')) {
            $ids = $request->iqa_ids ?? [];
            $questions = $request->iqa_questions;
            $answers = $request->iqa_answers ?? [];

            foreach ($questions as $index => $q) {
                $id = $ids[$index] ?? 0;
                $data = [
                    'atp_id' => $atp_id,
                    'interview_type' => 'iqa',
                    'question' => $q,
                    'answer' => $answers[$index] ?? '',
                ];
                
                $this->saveInterview($id, $data);
            }
        }

        // Trainers
        if ($request->has('train_questions')) {
            $ids = $request->train_ids ?? [];
            $questions = $request->train_questions;
            $answers = $request->train_answers ?? [];

            foreach ($questions as $index => $q) {
                $id = $ids[$index] ?? 0;
                $data = [
                    'atp_id' => $atp_id,
                    'interview_type' => 'train',
                    'question' => $q,
                    'answer' => $answers[$index] ?? '',
                ];
                
                $this->saveInterview($id, $data);
            }
        }

        return response()->json(['success' => true]);
    }

    private function saveInterview($id, $data) {
        if (!Schema::hasTable('eqa_007_interview')) return;

        if ($id > 0) {
            DB::table('eqa_007_interview')->where('interview_type', $data['interview_type'])->where('interview_id', $id)->update($data);
        } else {
            $data['added_by'] = auth()->id() ?? 1;
            $data['added_date'] = now();
            DB::table('eqa_007_interview')->insert($data);
        }
    }
}
