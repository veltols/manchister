<?php

namespace App\Http\Controllers\RC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrainingProvider;
use App\Models\TrainingProviderStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TrainingProviderController extends Controller
{
    public function index(Request $request)
    {
        $query = TrainingProvider::with(['status', 'creator'])->orderBy('atp_id', 'desc');

        if ($request->has('search') && $request->search != '') {
            $s = $request->search;
            $query->where(function($q) use ($s){
                $q->where('atp_name', 'LIKE', "%$s%") // Fixed column name
                  ->orWhere('atp_ref', 'LIKE', "%$s%")
                  ->orWhere('contact_name', 'LIKE', "%$s%");
            });
        }

        $providers = $query->paginate(15);
        
        return view('rc.atps.index', compact('providers'));
    }

    public function create()
    {
        $categories = \App\Models\TrainingProviderCategory::all();
        $types = \App\Models\TrainingProviderType::all();
        $cities = \App\Models\City::all();
        return view('rc.atps.create', compact('categories', 'types', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'atp_name' => 'required|string',
            'atp_category_id' => 'required|integer',
            'atp_type_id' => 'required|integer',
            'atp_email' => 'required|email',
            'contact_name' => 'required|string',
            'emirate_id' => 'required|integer',
        ]);

        $atp = new TrainingProvider();
        $atp->atp_ref = 'ATP-' . strtoupper(Str::random(6));
        $atp->atp_name = $request->atp_name;
        $atp->atp_category_id = $request->atp_category_id;
        $atp->atp_type_id = $request->atp_type_id;
        $atp->atp_email = $request->atp_email;
        $atp->atp_phone = $request->atp_phone ?? '';
        $atp->contact_name = $request->contact_name;
        $atp->emirate_id = $request->emirate_id;
        $atp->added_by = Auth::user()->employee_id ?? Auth::id();
        $atp->added_date = now();

        $atp->save();

        // Create initial contact record
        $contact = new \App\Models\TrainingProviderContact();
        $contact->atp_id = $atp->atp_id;
        $contact->contact_name = $request->contact_name;
        $contact->contact_phone = $request->atp_phone; // Using ATP phone as primary contact phone initially
        $contact->contact_email = $request->atp_email; // Using ATP email as primary contact email initially
        $contact->contact_designation = $request->contact_name_designation ?? '';
        $contact->save();

        return redirect()->route('rc.atps.index')->with('success', 'Training Provider created.');
    }
    
    public function show($id)
    {
        $atp = TrainingProvider::with(['category', 'type', 'city', 'contacts', 'faculty', 'learners.qualification', 'qualifications.faculty', 'qualifications.evidence', 'eqa', 'sed', 'qip.standardMain', 'locations', 'learnerHistory'])->findOrFail($id);
        
        // Calculate Compliance Scores
        $standards = \App\Models\QualityStandardMain::all();
        foreach($standards as $std) {
            $totalFilled = \App\Models\AtpCompliance::where('main_id', $std->main_id)->where('atp_id', $id)->count();
            $totalOk = \App\Models\AtpCompliance::where('main_id', $std->main_id)->where('atp_id', $id)->where('answer', 1)->count();
            
            $score = 0;
            if($totalFilled > 0){
                $score = ceil(($totalOk / $totalFilled) * 100);
            }
            $std->score = $score;
            
            // KPI Color Logic
            if($score <= 35) $std->kpi_color = 'text-red-600 bg-red-50 border-red-100';
            elseif($score <= 70) $std->kpi_color = 'text-amber-600 bg-amber-50 border-amber-100';
            else $std->kpi_color = 'text-emerald-600 bg-emerald-50 border-emerald-100';
        }

        return view('rc.atps.show', compact('atp', 'standards'));
    }
}
