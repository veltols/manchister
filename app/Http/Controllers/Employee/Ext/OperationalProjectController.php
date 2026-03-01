<?php

namespace App\Http\Controllers\Employee\Ext;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OperationalProject;
use App\Models\OperationalProjectMilestone;
use App\Models\OperationalProjectKpi;
use App\Models\StrategicPlan;
use App\Models\StrategicPlanKpi;
use Illuminate\Support\Facades\Auth;

class OperationalProjectController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $deptId = $user->employee ? $user->employee->department_id : 0;

        $projects = OperationalProject::where('department_id', $deptId)
            ->orderBy('project_id', 'desc')
            ->with(['plan', 'department'])
            ->withCount(['milestones', 'kpis'])
            ->paginate(10);

        return view('emp.ext.strategies_ops.index', compact('projects'));
    }

    public function create()
    {
        $plans = StrategicPlan::where('is_published', 1)->orderBy('plan_title')->get();
        return view('emp.ext.strategies_ops.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_code'            => 'required|string|max:50',
            'project_name'            => 'required|string|max:255',
            'project_description'     => 'required|string',
            'project_start_date'      => 'required|date',
            'project_end_date'        => 'required|date',
            'project_period'          => 'nullable|string',
            'plan_id'                 => 'required|integer',
            'project_analysis'        => 'nullable|string',
            'project_recommendations' => 'nullable|string',
        ]);

        $user   = Auth::user();
        $deptId = $user->employee ? $user->employee->department_id : 0;

        $count = OperationalProject::count() + 1;
        $ref   = 'OP-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        OperationalProject::create([
            'project_ref'             => $ref,
            'project_code'            => $request->project_code,
            'project_name'            => $request->project_name,
            'project_description'     => $request->project_description,
            'project_start_date'      => $request->project_start_date,
            'project_end_date'        => $request->project_end_date,
            'project_period'          => $request->project_period,
            'plan_id'                 => $request->plan_id,
            'project_analysis'        => $request->project_analysis,
            'project_recommendations' => $request->project_recommendations,
            'department_id'           => $deptId,
            'project_status_id'       => 1,
            'added_by'                => Auth::id() ?? 0,
            'added_date'              => now(),
        ]);

        return redirect()->route('emp.ext.strategies.projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show($id)
    {
        $project = OperationalProject::with([
            'plan',
            'department',
            'kpis.linkedKpi.objective',
            'kpis.linkedKpi.theme',
            'kpis.milestones',
            'milestones',
        ])->findOrFail($id);

        $availableKpis = $project->plan
            ? StrategicPlanKpi::where('plan_id', $project->plan_id)
                ->with(['objective', 'theme'])
                ->get()
            : collect();

        return view('emp.ext.strategies_ops.show', compact('project', 'availableKpis'));
    }

    public function update(Request $request, $id)
    {
        $project = OperationalProject::findOrFail($id);
        $project->update($request->only([
            'project_name', 'project_description', 'project_analysis',
            'project_recommendations', 'project_start_date', 'project_end_date', 'project_period',
        ]));
        return redirect()->route('emp.ext.strategies.projects.show', $id)
            ->with('success', 'Project updated.');
    }

    public function publish($id)
    {
        $project = OperationalProject::findOrFail($id);
        $project->update(['project_status_id' => 2]);
        return redirect()->route('emp.ext.strategies.projects.show', $id)
            ->with('success', 'Project published successfully.');
    }

    public function storeKpiLink(Request $request, $projectId)
    {
        $request->validate(['kpi_id' => 'required|integer']);

        $kpi = StrategicPlanKpi::findOrFail($request->kpi_id);

        $exists = OperationalProjectKpi::where('project_id', $projectId)
            ->where('linked_kpi_id', $kpi->kpi_id)->exists();

        if (!$exists) {
            OperationalProjectKpi::create([
                'project_id'    => $projectId,
                'linked_kpi_id' => $kpi->kpi_id,
                'kpi_id'        => $kpi->kpi_id,
                'plan_id'       => $kpi->plan_id,
                'theme_id'      => $kpi->theme_id,
                'objective_id'  => $kpi->objective_id,
                'added_by'      => Auth::id() ?? 0,
                'added_date'    => now(),
            ]);
        }

        return redirect()->route('emp.ext.strategies.projects.show', $projectId)
            ->with('success', 'KPI linked successfully.');
    }

    public function storeMilestone(Request $request, $projectId)
    {
        $request->validate([
            'milestone_title'       => 'required|string|max:255',
            'milestone_description' => 'nullable|string',
            'milestone_weight'      => 'required|integer|min:0|max:100',
            'kpi_id'                => 'required|integer',
            'start_date'            => 'required|date',
            'end_date'              => 'required|date',
        ]);

        $kpi   = StrategicPlanKpi::findOrFail($request->kpi_id);
        $count = OperationalProjectMilestone::where('kpi_id', $kpi->kpi_id)->count() + 1;

        OperationalProjectMilestone::create([
            'milestone_ref'         => ($kpi->kpi_ref ?? 'KPI') . '.' . $count,
            'milestone_title'       => $request->milestone_title,
            'milestone_description' => $request->milestone_description,
            'milestone_weight'      => $request->milestone_weight,
            'start_date'            => $request->start_date,
            'end_date'              => $request->end_date,
            'kpi_id'                => $kpi->kpi_id,
            'objective_id'          => $kpi->objective_id,
            'theme_id'              => $kpi->theme_id,
            'plan_id'               => $kpi->plan_id,
            'project_id'            => $projectId,
            'order_no'              => $count,
            'employee_id'           => Auth::user()->employee->employee_id ?? Auth::id(),
            'added_by'              => Auth::id() ?? 0,
            'added_date'            => now(),
        ]);

        return redirect()->route('emp.ext.strategies.projects.show', $projectId)
            ->with('success', 'Milestone added successfully.');
    }
}
