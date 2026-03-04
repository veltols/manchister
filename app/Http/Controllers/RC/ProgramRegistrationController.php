<?php

namespace App\Http\Controllers\RC;

use App\Http\Controllers\Controller;
use App\Models\Atp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramRegistrationController extends Controller
{
    private function getAtp(): ?Atp
    {
        $atpId = session('atp_id');
        if (!$atpId)
            return null;
        return Atp::find($atpId);
    }

    private function baseData(Atp $atp): array
    {
        return compact('atp');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 1. FACULTY DETAILS (Upload CVs/Certificates)
    // ─────────────────────────────────────────────────────────────────────────
    public function faculty()
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $faculties = DB::table('atps_list_faculties')->where('atp_id', $atp->atp_id)->get();
        $facultyTypes = DB::table('atps_list_faculties_types')->orderBy('faculty_type_id')->get();

        return view('rc.portal.program_registration.faculty', array_merge($this->baseData($atp), compact('faculties', 'facultyTypes')));
    }

    public function saveFaculty(Request $request)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $request->validate([
            'faculty_name' => 'required|string',
            'faculty_type_id' => 'required|integer',
            'educational_qualifications' => 'required|string',
            'years_experience' => 'required|integer',
            'certificate_name' => 'required|string',
        ]);

        $id = $request->faculty_id;
        $data = [
            'atp_id' => $atp->atp_id,
            'faculty_name' => $request->faculty_name,
            'faculty_type_id' => $request->faculty_type_id,
            'educational_qualifications' => $request->educational_qualifications,
            'years_experience' => $request->years_experience,
            'certificate_name' => $request->certificate_name,
        ];

        if ($id) {
            DB::table('atps_list_faculties')->where('faculty_id', $id)->where('atp_id', $atp->atp_id)->update($data);
        } else {
            DB::table('atps_list_faculties')->insert($data);
        }

        return redirect()->route('rc.portal.program_registration.faculty')->with('success', 'Faculty member saved.');
    }

    public function deleteFaculty($id)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');
        DB::table('atps_list_faculties')->where('faculty_id', $id)->where('atp_id', $atp->atp_id)->delete();
        return redirect()->route('rc.portal.program_registration.faculty')->with('success', 'Faculty member deleted.');
    }

    public function uploadCV(Request $request)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $request->validate([
            'faculty_id' => 'required|integer',
            'faculty_cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'faculty_certificate' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $updateData = [];

        if ($request->hasFile('faculty_cv')) {
            $path = $request->file('faculty_cv')->store('faculty_docs/' . $atp->atp_id, 'public');
            $updateData['faculty_cv'] = $path;
        }

        if ($request->hasFile('faculty_certificate')) {
            $path = $request->file('faculty_certificate')->store('faculty_docs/' . $atp->atp_id, 'public');
            $updateData['faculty_certificate'] = $path;
        }

        if (count($updateData) > 0) {
            DB::table('atps_list_faculties')->where('faculty_id', $request->faculty_id)->where('atp_id', $atp->atp_id)->update($updateData);
        }

        return redirect()->route('rc.portal.program_registration.faculty')->with('success', 'Attachments uploaded successfully.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 2. QUALIFICATION MAPPING
    // ─────────────────────────────────────────────────────────────────────────
    public function qualificationMapping()
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $qualifications = DB::table('atps_list_qualifications')->where('atp_id', $atp->atp_id)->get();

        // Load mapping for each qualification
        $faculties = DB::table('atps_list_faculties')->where('atp_id', $atp->atp_id)->get();
        $mappings = DB::table('atps_list_qualifications_faculties')->where('atp_id', $atp->atp_id)->get();

        foreach ($qualifications as $q) {
            $mappedFacultyIds = $mappings->where('qualification_id', $q->qualification_id)->pluck('faculty_id')->toArray();

            $names = $faculties->whereIn('faculty_id', $mappedFacultyIds)->pluck('faculty_name')->toArray();
            $q->mapped_faculty_names = count($names) > 0 ? implode(', ', $names) : 'None';
            $q->mapped_faculty_ids = $mappedFacultyIds;
        }

        return view('rc.portal.program_registration.qualification_mapping', array_merge($this->baseData($atp), compact('qualifications', 'faculties')));
    }

    public function saveQualificationMapping(Request $request)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $request->validate([
            'qualification_id' => 'required|integer',
            'faculty_id' => 'required|array',
            'faculty_id.*' => 'integer'
        ]);

        $qid = $request->qualification_id;

        // Clear existing mappings
        DB::table('atps_list_qualifications_faculties')
            ->where('atp_id', $atp->atp_id)
            ->where('qualification_id', $qid)
            ->delete();

        // Insert new mappings
        $inserts = [];
        foreach ($request->faculty_id as $fid) {
            $inserts[] = [
                'atp_id' => $atp->atp_id,
                'qualification_id' => $qid,
                'faculty_id' => $fid
            ];
        }

        if (count($inserts) > 0) {
            DB::table('atps_list_qualifications_faculties')->insert($inserts);
        }

        return redirect()->route('rc.portal.program_registration.qualification_mapping')->with('success', 'Mapping updated.');
    }


    // ─────────────────────────────────────────────────────────────────────────
    // 3. FINAL SUBMISSION
    // ─────────────────────────────────────────────────────────────────────────
    public function submit()
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        return view('rc.portal.program_registration.submit', $this->baseData($atp));
    }

    public function processSubmission(Request $request)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $request->validate([
            'terms' => 'accepted'
        ]);

        // Same logic as serv_submit.php
        $is_renew = 0;

        // Count exist forms
        $count = DB::table('atps_prog_register_req')
            ->where('atp_id', $atp->atp_id)
            ->where('is_renew', $is_renew)
            ->count();

        if ($count > 0) {
            DB::table('atps_prog_register_req')
                ->where('atp_id', $atp->atp_id)
                ->where('is_renew', $is_renew)
                ->update([
                    'submitted_date' => now(),
                    'form_status' => 'pending',
                    'is_submitted' => 1
                ]);
        } else {
            DB::table('atps_prog_register_req')->insert([
                'atp_id' => $atp->atp_id,
                'added_date' => now(),
                'submitted_date' => now(),
                'is_submitted' => 1,
                'form_status' => 'pending',
                'is_renew' => $is_renew
            ]);
        }

        // Update atps_list to flag phase wait
        DB::table('atps_list')
            ->where('atp_id', $atp->atp_id)
            ->update([
                'is_phase_ok' => 0
            ]);

        // Insert Atp Log
        \App\Models\AtpLog::create([
            'atp_id' => $atp->atp_id,
            'log_action' => "ATP Submitted Program Registration",
            'log_date' => now()->toDateTimeString(),
            'logger_type' => 'atps_list',
            'log_dept' => 'R&C',
            'logged_by' => $atp->atp_id
        ]);

        return redirect()->route('rc.portal.dashboard')->with('success', 'Program Registration Submitted Successfully.');
    }
}
