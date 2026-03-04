<?php

namespace App\Http\Controllers\RC;

use App\Http\Controllers\Controller;
use App\Models\Atp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccreditationController extends Controller
{
    // ─── Guard helper ─────────────────────────────────────────────────────────
    private function getAtp(): ?Atp
    {
        $atpId = session('atp_id');
        if (!$atpId)
            return null;
        return Atp::with(['emirate', 'category'])->find($atpId);
    }

    // ─── Shared data for all sub-forms ────────────────────────────────────────
    private function baseData(Atp $atp): array
    {
        $emirateName = optional($atp->emirate)->city_name ?? '';
        $existing = DB::table('atps_form_init')->where('atp_id', $atp->atp_id)->first();
        $faculties = DB::table('atps_list_faculties_types')->orderBy('faculty_type_id')->get();

        return compact('atp', 'emirateName', 'existing', 'faculties');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 1. INITIAL FORM
    // ─────────────────────────────────────────────────────────────────────────
    public function initialForm()
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $existing = DB::table('atps_form_init')->where('atp_id', $atp->atp_id)->first();
        $sectors = DB::table('atps_list_categories')->get();

        return view(
            'rc.portal.accreditation.initial_form',
            array_merge($this->baseData($atp), compact('sectors'))
        );
    }

    public function saveInitialForm(Request $request)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $request->validate([
            'est_name' => 'required|string|max:255',
            'est_name_ar' => 'required|string|max:255',
            'iqa_name' => 'required|string|max:255',
            'atp_category_id' => 'required|integer',
            'registration_expiry' => 'required|date',
            'area_name' => 'required|string|max:255',
            'street_name' => 'required|string|max:255',
            'building_name' => 'required|string|max:255',
        ]);

        $data = [
            'atp_id' => $atp->atp_id,
            'est_name' => $request->est_name,
            'est_name_ar' => $request->est_name_ar,
            'iqa_name' => $request->iqa_name,
            'email_address' => $atp->atp_email,
            'atp_category_id' => $request->atp_category_id,
            'registration_expiry' => $request->registration_expiry,
            'area_name' => $request->area_name,
            'street_name' => $request->street_name,
            'building_name' => $request->building_name,
        ];

        $exists = DB::table('atps_form_init')->where('atp_id', $atp->atp_id)->exists();
        if ($exists) {
            DB::table('atps_form_init')->where('atp_id', $atp->atp_id)->update($data);
        } else {
            $data['added_date'] = now();
            $data['form_status'] = 'pending_submission';
            DB::table('atps_form_init')->insert($data);
        }

        return redirect()->route('rc.portal.accreditation.initial_form')
            ->with('success', 'Initial form saved successfully.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 2. QUALIFICATIONS
    // ─────────────────────────────────────────────────────────────────────────
    public function qualifications()
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $qualifications = DB::table('atps_list_qualifications')
            ->where('atp_id', $atp->atp_id)
            ->get();

        return view(
            'rc.portal.accreditation.qualifications',
            array_merge($this->baseData($atp), compact('qualifications'))
        );
    }

    public function saveQualification(Request $request)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $request->validate([
            'qualification_name' => 'required|string',
            'qualification_type' => 'required|string',
            'qualification_category' => 'required|string',
            'emirates_level' => 'required',
            'qulaification_credits' => 'required|string',
            'mode_of_delivery' => 'required|string',
        ]);

        $id = $request->qualification_id;
        $data = [
            'atp_id' => $atp->atp_id,
            'qualification_name' => $request->qualification_name,
            'qualification_type' => $request->qualification_type,
            'qualification_category' => $request->qualification_category,
            'emirates_level' => $request->emirates_level,
            'qulaification_credits' => $request->qulaification_credits,
            'mode_of_delivery' => $request->mode_of_delivery,
        ];

        if ($id) {
            DB::table('atps_list_qualifications')->where('qualification_id', $id)->where('atp_id', $atp->atp_id)->update($data);
        } else {
            DB::table('atps_list_qualifications')->insert($data);
        }

        return redirect()->route('rc.portal.accreditation.qualifications')
            ->with('success', 'Qualification saved.');
    }

    public function deleteQualification($id)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');
        DB::table('atps_list_qualifications')->where('qualification_id', $id)->where('atp_id', $atp->atp_id)->delete();
        return redirect()->route('rc.portal.accreditation.qualifications')->with('success', 'Qualification deleted.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 3. FACULTY DETAILS
    // ─────────────────────────────────────────────────────────────────────────
    public function faculty()
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $faculties = DB::table('atps_list_faculties')->where('atp_id', $atp->atp_id)->get();
        $facultyTypes = DB::table('atps_list_faculties_types')->orderBy('faculty_type_id')->get();

        return view(
            'rc.portal.accreditation.faculty',
            array_merge($this->baseData($atp), compact('faculties', 'facultyTypes'))
        );
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

        return redirect()->route('rc.portal.accreditation.faculty')->with('success', 'Faculty member saved.');
    }

    public function deleteFaculty($id)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');
        DB::table('atps_list_faculties')->where('faculty_id', $id)->where('atp_id', $atp->atp_id)->delete();
        return redirect()->route('rc.portal.accreditation.faculty')->with('success', 'Faculty member deleted.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 4. LEARNERS STATISTICS
    // ─────────────────────────────────────────────────────────────────────────
    public function learners()
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $statistics = DB::table('atps_learners_statistics')->where('atp_id', $atp->atp_id)->get();
        $qualifications = DB::table('atps_list_qualifications')->where('atp_id', $atp->atp_id)->get();

        return view(
            'rc.portal.accreditation.learners',
            array_merge($this->baseData($atp), compact('statistics', 'qualifications'))
        );
    }

    public function saveLearners(Request $request)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $request->validate([
            'qualification_id' => 'required',
            'statistic_type' => 'required|in:registered,awarded',
            'y1_value' => 'nullable|integer',
            'y2_value' => 'nullable|integer',
            'y3_value' => 'nullable|integer',
            'y4_value' => 'nullable|integer',
        ]);

        $data = [
            'atp_id' => $atp->atp_id,
            'qualification_id' => $request->qualification_id,
            'statistic_type' => $request->statistic_type,
            'y1_value' => $request->y1_value ?? 0,
            'y2_value' => $request->y2_value ?? 0,
            'y3_value' => $request->y3_value ?? 0,
            'y4_value' => $request->y4_value ?? 0,
        ];

        DB::table('atps_learners_statistics')->insert($data);

        return redirect()->route('rc.portal.accreditation.learners')->with('success', 'Statistic added.');
    }

    public function deleteLearnerStatistic($id)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');
        DB::table('atps_learners_statistics')->where('statistic_id', $id)->where('atp_id', $atp->atp_id)->delete();
        return redirect()->route('rc.portal.accreditation.learners')->with('success', 'Statistic removed.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 5. ELECTRONIC SYSTEMS
    // ─────────────────────────────────────────────────────────────────────────
    public function electronicSystems()
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $platforms = DB::table('atps_electronic_systems')->where('atp_id', $atp->atp_id)->get();

        return view(
            'rc.portal.accreditation.electronic_systems',
            array_merge($this->baseData($atp), compact('platforms'))
        );
    }

    public function saveElectronicSystems(Request $request)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $request->validate([
            'platform_name' => 'required|string|max:255',
            'platform_purpose' => 'required|string',
        ]);

        $data = [
            'atp_id' => $atp->atp_id,
            'platform_name' => $request->platform_name,
            'platform_purpose' => $request->platform_purpose,
        ];

        DB::table('atps_electronic_systems')->insert($data);

        return redirect()->route('rc.portal.accreditation.electronic_systems')->with('success', 'Platform added.');
    }

    public function deleteElectronicSystem($id)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');
        DB::table('atps_electronic_systems')->where('platform_id', $id)->where('atp_id', $atp->atp_id)->delete();
        return redirect()->route('rc.portal.accreditation.electronic_systems')->with('success', 'Platform removed.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 6. ATTACHMENTS
    // ─────────────────────────────────────────────────────────────────────────
    public function attachments()
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');
        $existing = DB::table('atps_form_init')->where('atp_id', $atp->atp_id)->first();
        return view(
            'rc.portal.accreditation.attachments',
            array_merge($this->baseData($atp), compact('existing'))
        );
    }

    public function saveAttachments(Request $request)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $fields = [
            'delivery_plan',
            'org_chart',
            'site_plan',
            'sed_form',
            'atp_logo'
        ];

        $updateData = [];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $path = $file->store('atp_attachments/' . $atp->atp_id, 'public');
                $updateData[$field] = $path;
            }
        }

        if (count($updateData) > 0) {
            DB::table('atps_form_init')
                ->where('atp_id', $atp->atp_id)
                ->update($updateData);
        }

        return redirect()->route('rc.portal.accreditation.attachments')->with('success', 'Attachments saved successfully.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 7. FINAL SUBMISSION
    // ─────────────────────────────────────────────────────────────────────────
    public function submit()
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        $existing = DB::table('atps_form_init')->where('atp_id', $atp->atp_id)->first();

        // Check if basic info is filled
        $isComplete = $existing && $existing->est_name && $existing->iqa_name;

        return view(
            'rc.portal.accreditation.submit',
            array_merge($this->baseData($atp), compact('existing', 'isComplete'))
        );
    }

    public function processSubmission(Request $request)
    {
        $atp = $this->getAtp();
        if (!$atp)
            return redirect()->route('login');

        DB::table('atps_form_init')
            ->where('atp_id', $atp->atp_id)
            ->update([
                'is_submitted' => 1,
                'submitted_date' => now(),
                'form_status' => 'submitted',
            ]);

        return redirect()->route('rc.portal.dashboard')->with('success', 'Your accreditation request has been submitted successfully.');
    }
}
