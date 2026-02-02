<?php

namespace App\Http\Controllers\Employee\Ext;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StrategicStudy;
use Illuminate\Support\Facades\Auth;

class StrategicStudyController extends Controller
{
    public function index()
    {
        $studies = StrategicStudy::orderBy('study_id', 'desc')->paginate(10);
        return view('emp.ext.strategies_self_studies.index', compact('studies'));
    }

    public function create()
    {
        return view('emp.ext.strategies_self_studies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'study_title' => 'required|string|max:255',
            'study_overview' => 'required|string',
        ]);

        $study = new StrategicStudy();
        $study->study_title = $request->study_title;
        $study->study_overview = $request->study_overview;

        $study->study_status_id = 1; // Draft
        $study->added_by = Auth::id() ?? 0;
        $study->added_date = now();
        $study->study_ref = 'STUD-' . strtoupper(uniqid());

        $study->save();

        return redirect()->route('emp.ext.strategies.self_studies.index')->with('success', 'Study created successfully.');
    }

    public function show($id)
    {
        $study = StrategicStudy::findOrFail($id);
        return view('emp.ext.strategies_self_studies.show', compact('study'));
    }
}
