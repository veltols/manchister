<?php

namespace App\Http\Controllers\RC;

use App\Http\Controllers\Controller;
use App\Models\Atp;
use App\Models\AtpContact;
use App\Models\AtpLog;
use App\Models\AtpPass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrainingProviderController extends Controller
{
    public function create()
    {
        return view('rc.atps.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'atp_name' => 'required|string|max:255',
            'atp_email' => 'required|email|max:255',
            'contact_name' => 'required|string|max:255',
            'atp_phone' => 'nullable|string|max:50',
        ]);

        $employeeId = Auth::user()->employee->employee_id ?? 0;

        DB::transaction(function () use ($request, $employeeId) {

            // Step 1: INSERT atps_list
            $atp = new Atp();
            $atp->atp_name = $request->atp_name;
            $atp->atp_email = $request->atp_email;
            $atp->contact_name = $request->contact_name;
            $atp->atp_phone = $request->atp_phone ?? '';
            $atp->added_by = $employeeId;
            $atp->added_date = now()->format('Y-m-d H:i:00');
            $atp->atp_status_id = 1;
            $atp->atp_category_id = 0;
            $atp->atp_type_id = 0;
            $atp->accreditation_type = 1;
            $atp->phase_id = 1;
            $atp->is_phase_ok = 1;
            $atp->todo_id = 1;
            $atp->atp_logo = 'no-img.png';
            $atp->emirate_id = 0;
            $atp->atp_ref = 'TEMP';
            $atp->save();

            // Step 2: INSERT atps_list_contacts
            AtpContact::create([
                'atp_id' => $atp->atp_id,
                'contact_name' => $request->contact_name,
                'contact_phone' => $request->atp_phone ?? '',
                'contact_email' => $request->atp_email,
                'contact_designation' => '',
            ]);

            // Step 3: INSERT users_list (ATP portal login account)
            DB::table('users_list')->insert([
                'user_id' => $atp->atp_id,
                'user_email' => $request->atp_email,
                'user_type' => 'atp',
                'int_ext' => 'ext',
                'user_family' => 'atps_list',
            ]);

            // Step 4: INSERT atps_list_pass (default bcrypt password)
            AtpPass::create([
                'atp_id' => $atp->atp_id,
                'pass_value' => '$2y$10$vyDJsXaSPt03tXcY8z/lBuLHIEyTFxbHkA7eB2L1uA36d.VaXzs/e',
                'is_active' => 1,
            ]);

            // Step 5: UPDATE atp_ref with proper generated code
            $name = preg_replace('/[^A-Za-z0-9\-]/', '', $atp->atp_name);
            $words = explode(' ', trim($atp->atp_name));
            $prefix = '';
            if (count($words) > 1) {
                $count = 0;
                foreach ($words as $word) {
                    if (trim($word) === '')
                        continue;
                    $prefix .= strtoupper($word[0]);
                    if (++$count >= 3)
                        break;
                }
            } else {
                $len = strlen($name) - 1;
                $prefix = strtoupper($name[0]) . strtoupper($name[$len]) . '0';
            }
            $atp->atp_ref = $prefix . now()->format('my') . 'NA0' . $atp->atp_id;
            $atp->last_updated = now()->format('Y-m-d H:i:00');
            $atp->save();

            // Step 6: INSERT atps_list_logs
            AtpLog::create([
                'atp_id' => $atp->atp_id,
                'log_action' => 'ATP Added',
                'log_date' => now()->format('Y-m-d H:i:00'),
                'logger_type' => 'employees_list',
                'log_dept' => 'R&C',
                'logged_by' => $employeeId,
            ]);
        });

        return redirect()->route('emp.ext.atps.index')
            ->with('success', 'Training Provider added successfully.');
    }
}
