<?php

namespace App\Http\Controllers\RC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TrainingProvider;

class PortalController extends Controller
{
    public function dashboard()
    {
        if(!session('atp_id')) return redirect()->route('login');
        
        $atp = TrainingProvider::find(session('atp_id'));
        return view('rc.portal.dashboard', compact('atp'));
    }

    public function wizardStep1()
    {
        if(!session('atp_id')) return redirect()->route('login');
        $atp = TrainingProvider::find(session('atp_id'));
        
        return view('rc.portal.wizard.step1', compact('atp'));
    }

    public function submitStep1(Request $request)
    {
        // Save form logic here (Legacy: atps_form_init)
        // For migration demo, we will just update status and redirect
        
        $atpId = session('atp_id');
        
        // Mock save
        // DB::table('atps_form_init')->insert([...]);
        
        return redirect()->route('rc.portal.dashboard')->with('success', 'Form Submitted');
    }
}
