<?php

namespace App\Http\Controllers\RC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Atp;
use Illuminate\Support\Facades\Auth;

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

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $atp = new Atp();
        $atp->atp_name = $request->atp_name;
        $atp->atp_email = $request->atp_email;
        $atp->contact_name = $request->contact_name;
        $atp->atp_phone = $request->atp_phone;
        $atp->added_by = $employeeId;
        $atp->added_date = now();
        $atp->atp_status_id = 1; // Default to Pending
        $atp->atp_ref = 'ATP-' . strtoupper(uniqid()); // Generate dummy ref for now
        $atp->atp_category_id = 0;
        $atp->atp_type_id = 0;
        $atp->phase_id = 0;
        $atp->is_phase_ok = 0;
        $atp->todo_id = 0;
        $atp->save();

        return redirect()->route('emp.ext.atps.index')->with('success', 'Training Provider added successfully.');
    }
}
