<?php

namespace App\Http\Controllers\Employee\Ext;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OperationalProject;
use App\Models\StrategicPlan;
use Illuminate\Support\Facades\Auth;

class OperationalProjectController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $deptId = $user->employee ? $user->employee->department_id : 0;

        $projects = OperationalProject::where('department_id', $deptId)
            ->orderBy('project_id', 'desc')
            ->with(['plan', 'department'])
            ->paginate(10);

        return view('emp.ext.strategies_ops.index', compact('projects'));
    }

    public function create()
    {
        // Get published strategic plans for dropdown
        $plans = StrategicPlan::where('plan_status_id', 2)->orderBy('plan_title')->get();
        return view('emp.ext.strategies_ops.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_code' => 'required|string|max:50',
            'project_name' => 'required|string|max:255',
            'project_description' => 'required|string',
            'project_start_date' => 'required|date',
            'project_end_date' => 'required|date',
            'project_period' => 'nullable|string',
            'plan_id' => 'required|integer',
            'project_analysis' => 'nullable|string',
            'project_recommendations' => 'nullable|string',
        ]);

        $user = Auth::user();
        $deptId = $user->employee ? $user->employee->department_id : 0;

        $project = new OperationalProject();
        $project->project_code = $request->project_code;
        $project->project_name = $request->project_name;
        $project->project_description = $request->project_description;
        $project->project_start_date = $request->project_start_date;
        $project->project_end_date = $request->project_end_date;
        $project->project_period = $request->project_period;
        $project->plan_id = $request->plan_id;
        $project->project_analysis = $request->project_analysis;
        $project->project_recommendations = $request->project_recommendations;

        $project->department_id = $deptId;
        $project->project_status_id = 1; // Draft
        $project->added_by = Auth::id() ?? 0;
        $project->added_date = now();
        $project->project_ref = 'PROJ-' . strtoupper(uniqid());

        $project->save();

        return redirect()->route('emp.ext.strategies.projects.index')->with('success', 'Project created successfully.');
    }

    public function show($id)
    {
        $project = OperationalProject::with(['plan', 'department'])->findOrFail($id);
        return view('emp.ext.strategies_ops.show', compact('project'));
    }
}
