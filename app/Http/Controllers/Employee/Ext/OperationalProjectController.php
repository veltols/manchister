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
use App\Models\Employee;
use App\Models\Department;
use App\Models\SystemLog;
use App\Models\TaskStatus;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class OperationalProjectController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $deptId = $user->employee ? $user->employee->department_id : 0;
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $projects = OperationalProject::where('added_by', $employeeId)
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
        $deptId = StrategicPlan::where('plan_id', $request->plan_id)->first();
 
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
            'department_id'           => $deptId->plan_level ?? 0,
            'project_status_id'       => 1,
            'added_by'                => auth()->user()->user_id,
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
            'milestones.owner',
        ])->findOrFail($id);

        $availableKpis = $project->plan
            ? StrategicPlanKpi::where('plan_id', $project->plan_id)
                ->whereIn('department_id', [$project->department_id, 0])
                ->with(['objective', 'theme'])
                ->get()
            : collect();

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $managerDepartments = \App\Models\Department::whereNotNull('line_manager_id')
            ->where('line_manager_id', '!=', 0)
            ->with(['lineManager' => function($q) {
                $q->where('is_deleted', 0)
                  ->where('is_hidden', 0)
                  ->whereHas('systemUser', function($sq) {
                      $sq->where('is_active', 1);
                  });
            }])
            ->orderBy('department_name')
            ->get();

        return view('emp.ext.strategies_ops.show', compact('project', 'availableKpis', 'managerDepartments', 'employeeId'));
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
                'added_by'      => auth()->user()->user_id,
                'added_date'    => now(),
            ]);
        }

        return redirect()->route('emp.ext.strategies.projects.show', $projectId)
            ->with('success', 'KPI linked successfully.');
    }

    // public function storeMilestone(Request $request, $projectId)
    // {
    //     $request->validate([
    //         'milestone_title'       => 'required|string|max:255',
    //         'milestone_description' => 'nullable|string',
    //         'milestone_weight'      => 'required|integer|min:0|max:100',
    //         'kpi_id'                => 'required|integer',
    //         'employee_id'           => 'required|integer',
    //         'start_date'            => 'required|date',
    //         'end_date'              => 'required|date',
    //     ]);

    //     $kpi   = StrategicPlanKpi::findOrFail($request->kpi_id);
    //     $count = OperationalProjectMilestone::where('kpi_id', $kpi->kpi_id)->count() + 1;

    //     OperationalProjectMilestone::create([
    //         'milestone_ref'         => ($kpi->kpi_ref ?? 'KPI') . '.' . $count,
    //         'milestone_title'       => $request->milestone_title,
    //         'milestone_description' => $request->milestone_description,
    //         'milestone_weight'      => $request->milestone_weight,
    //         'start_date'            => $request->start_date,
    //         'end_date'              => $request->end_date,
    //         'kpi_id'                => $kpi->kpi_id,
    //         'objective_id'          => $kpi->objective_id,
    //         'theme_id'              => $kpi->theme_id,
    //         'plan_id'               => $kpi->plan_id,
    //         'project_id'            => $projectId,
    //         'order_no'              => $count,
    //         'employee_id'           => $request->employee_id,
    //         'added_by'              => auth()->user()->user_id,
    //         'added_date'            => now(),
    //     ]);

    //     return redirect()->route('emp.ext.strategies.projects.show', $projectId)
    //         ->with('success', 'Milestone added successfully.');
    // }


public function storeMilestone(Request $request, $projectId)
{
    $request->validate([
        'milestone_title'       => 'required|string|max:255',
        'milestone_description' => 'nullable|string',
        'milestone_weight'      => 'required|integer|min:0|max:100',
        'kpi_id'                => 'required|integer',
        'employee_id'           => 'required|integer',
        'start_date'            => 'required|date',
        'end_date'              => 'required|date',
    ]);

    DB::transaction(function () use ($request, $projectId) {

        $kpi = StrategicPlanKpi::findOrFail($request->kpi_id);

        $count = OperationalProjectMilestone::where('kpi_id', $kpi->kpi_id)->count() + 1;

        // Create Milestone
        $milestone = OperationalProjectMilestone::create([
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
            'employee_id'           => $request->employee_id,
            'added_by'              => auth()->user()->user_id,
            'added_date'            => now(),
        ]);

        $employee = Auth::user()->employee;
        $employeeId = $employee ? $employee->employee_id : 0;

        $pendingLineManagerId = Department::where(
            'department_id',
           $request->employee_id
        )->value('line_manager_id');
        $task = new Task();
        $task->task_title = $milestone->milestone_title;
        $task->task_description = $milestone->milestone_description;

        $task->task_assigned_date = $milestone->start_date;
        $task->task_due_date = $milestone->end_date;

        $task->assigned_by = $employeeId;
        $task->assigned_to = 0;
        $task->department_id =  $request->employee_id;
        $task->pending_line_manager_id = $pendingLineManagerId;

        $task->priority_id = 2;
        $task->parent_task_id = 0;
        $task->operational_project_id = $milestone->project_id;
        $task->operational_milestone_id = $milestone->milestone_id;
        // Optional (recommended)
        // $task->operational_milestone_id = $milestone->milestone_id;

        $firstStatus = TaskStatus::orderBy('status_id')->first();
        $task->status_id = $firstStatus ? $firstStatus->status_id : 1;
     
        $task->save();


        // Notify Department Manager
        if ($pendingLineManagerId) {
            \App\Services\NotificationService::send(
                "A new task has been created from the Operational Milestone '{$milestone->milestone_title}' and requires your review and assignment.",
                "emp/tasks/pending",
                $pendingLineManagerId
            );
        }

        // System Log
        SystemLog::create([
            'log_action'    => 'Task_Added',
            'log_remark'    => "Task was automatically created from Operational Milestone '{$milestone->milestone_title}' and is awaiting department manager assignment.",
            'related_table' => 'tasks_list',
            'related_id'    => $task->task_id,
            'log_date'      => now(),
            'logged_by'     => $employeeId,
            'logger_type'   => 'employees_list',
            'log_type'      => 'int',
        ]);
    });

    return redirect()
        ->route('emp.ext.strategies.projects.show', $projectId)
        ->with('success', 'Operational milestone and related task created successfully.');
}
    public function destroyKpiLink($id)
    {
        $pk = OperationalProjectKpi::findOrFail($id);
        $projectId = $pk->project_id;
        $pk->delete();
        return redirect()->route('emp.ext.strategies.projects.show', $projectId)
            ->with('success', 'KPI link removed.');
    }

    public function destroyMilestone($id)
    {
        $ms = OperationalProjectMilestone::findOrFail($id);
        $projectId = $ms->project_id;
        $ms->delete();
        return redirect()->route('emp.ext.strategies.projects.show', $projectId)
            ->with('success', 'Milestone deleted.');
    }
}
