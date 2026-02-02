<?php

namespace App\Http\Controllers\EQA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ATP;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    // Generic method to load a form
    public function show($form_id, $atp_id)
    {
        $atp = ATP::findOrFail($atp_id);

        // Map form_id to view
        $viewMap = [
            '003' => 'eqa.forms.003',
            '008' => 'eqa.forms.008',
            '014' => 'eqa.forms.014',
            '028' => 'eqa.forms.028',
            '006' => 'eqa.forms.006',
        ];

        if (!array_key_exists($form_id, $viewMap)) {
            abort(404, 'Form not found or migration pending.');
        }

        // Load existing data
        $formData = DB::table('eqa_' . $form_id)->where('atp_id', $atp_id)->first();

        // Context Data
        $qualifications = [];
        $faculties = [];

        if ($form_id == '008') {
            // These tables might be `atps_list_qualifications` and `atps_list_faculties` in legacy DB
            // Assuming simplified model usage or direct DB query if models don't exist yet
            $qualifications = DB::table('atps_list_qualifications')->where('atp_id', $atp_id)->get();
            $faculties = DB::table('atps_list_faculties')->where('atp_id', $atp_id)->get();
        }

        return view($viewMap[$form_id], compact('atp', 'formData', 'form_id', 'qualifications', 'faculties'));
    }

    public function store($form_id, $atp_id, Request $request)
    {
        // Generic Store/Update logic
        // In real app, might want specific FormRequests per form

        $tableName = 'eqa_' . $form_id;

        // Filter request to only save columns that exist in the table (simplified)
        // or just dump json for now.
        // For migration faithful:

        $data = $request->except(['_token']);
        $data['atp_id'] = $atp_id;
        $data['updated_at'] = now();

        DB::table($tableName)->updateOrInsert(
            ['atp_id' => $atp_id],
            $data
        );

        return redirect()->back()->with('success', 'Form saved successfully.');
    }
}
