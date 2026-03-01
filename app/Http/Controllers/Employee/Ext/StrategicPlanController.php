<?php

namespace App\Http\Controllers\Employee\Ext;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StrategicPlan;
use App\Models\StrategicPlanTheme;
use App\Models\StrategicPlanObjective;
use App\Models\StrategicPlanObjectiveType;
use App\Models\StrategicPlanKpi;
use App\Models\StrategicPlanKpiFreq;
use App\Models\StrategicPlanMilestone;
use App\Models\StrategicPlanExternalMap;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class StrategicPlanController extends Controller
{
    // ─────────────────────────────────────────────────────
    //  PLANS
    // ─────────────────────────────────────────────────────

    public function index()
    {
        $plans = StrategicPlan::orderBy('plan_id', 'desc')
            ->withCount(['themes', 'objectives'])
            ->with('department')
            ->paginate(10);

        return view('emp.ext.strategies.index', compact('plans'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('emp.ext.strategies.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_title'   => 'required|string|max:255',
            'plan_from'    => 'required|integer',
            'plan_to'      => 'required|integer',
            'plan_level'   => 'required|integer',
            'plan_vision'  => 'nullable|string',
            'plan_mission' => 'nullable|string',
            'plan_values'  => 'nullable|string',
            'plan_period'  => 'nullable|string',
        ]);

        $plan = new StrategicPlan();
        $plan->plan_title   = $request->plan_title;
        $plan->plan_from    = $request->plan_from;
        $plan->plan_to      = $request->plan_to;
        $plan->plan_period  = $request->plan_period;
        $plan->plan_level   = $request->plan_level;
        $plan->plan_vision  = $request->plan_vision;
        $plan->plan_mission = $request->plan_mission;
        $plan->plan_values  = $request->plan_values;
        $plan->plan_status_id = 1; // Draft
        $plan->is_published   = 0;
        $plan->added_by    = Auth::id() ?? 0;
        $plan->added_date  = now();
        $plan->plan_ref    = 'SP-' . strtoupper(uniqid());
        $plan->save();

        return redirect()->route('emp.ext.strategies.index')
            ->with('success', 'Strategic Plan created successfully.');
    }

    public function show($id)
    {
        $plan = StrategicPlan::with([
            'department',
            'themes.objectives.kpis.milestones',
            'externalMaps.objective',
            'internalMaps.department',
            'internalMaps.objective',
        ])->findOrFail($id);

        $objectiveTypes = StrategicPlanObjectiveType::all();
        $frequencies    = StrategicPlanKpiFreq::all();
        $departments    = Department::all();

        return view('emp.ext.strategies.show', compact('plan', 'objectiveTypes', 'frequencies', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $plan = StrategicPlan::findOrFail($id);
        $request->validate([
            'plan_title'   => 'required|string|max:255',
            'plan_from'    => 'required',
            'plan_to'      => 'required',
            'plan_level'   => 'required',
        ]);

        $plan->plan_title   = $request->plan_title;
        $plan->plan_from    = $request->plan_from;
        $plan->plan_to      = $request->plan_to;
        $plan->plan_period  = $request->plan_period;
        $plan->plan_level   = $request->plan_level;
        $plan->plan_vision  = $request->plan_vision;
        $plan->plan_mission = $request->plan_mission;
        $plan->plan_values  = $request->plan_values;
        $plan->save();

        return redirect()->route('emp.ext.strategies.show', $id)
            ->with('success', 'Plan updated successfully.');
    }

    public function publish($id)
    {
        $plan = StrategicPlan::findOrFail($id);
        $plan->plan_status_id = 2;
        $plan->is_published   = 1;
        $plan->save();

        return redirect()->route('emp.ext.strategies.show', $id)
            ->with('success', 'Plan published successfully.');
    }

    // ─────────────────────────────────────────────────────
    //  THEMES
    // ─────────────────────────────────────────────────────

    public function storeTheme(Request $request, $planId)
    {
        $request->validate([
            'theme_title'       => 'required|string|max:100',
            'theme_description' => 'nullable|string',
            'theme_weight'      => 'nullable|integer|min:0|max:100',
        ]);

        $theme = new StrategicPlanTheme();
        $theme->plan_id           = $planId;
        $theme->theme_title       = $request->theme_title;
        $theme->theme_description = $request->theme_description;
        $theme->theme_weight      = $request->theme_weight ?? 0;
        $theme->order_no          = StrategicPlanTheme::where('plan_id', $planId)->max('order_no') + 1;
        $theme->added_by          = Auth::id() ?? 0;
        $theme->added_date        = now();
        $theme->theme_ref         = 'TH-' . strtoupper(uniqid());
        $theme->save();

        return redirect()->route('emp.ext.strategies.show', $planId)
            ->with('success', 'Theme added successfully.');
    }

    public function updateTheme(Request $request, $planId, $themeId)
    {
        $theme = StrategicPlanTheme::where('plan_id', $planId)->findOrFail($themeId);
        $theme->theme_title       = $request->theme_title;
        $theme->theme_description = $request->theme_description;
        $theme->theme_weight      = $request->theme_weight ?? $theme->theme_weight;
        $theme->save();

        return redirect()->route('emp.ext.strategies.show', $planId)
            ->with('success', 'Theme updated successfully.');
    }

    // ─────────────────────────────────────────────────────
    //  OBJECTIVES
    // ─────────────────────────────────────────────────────

    public function storeObjective(Request $request, $planId)
    {
        $request->validate([
            'theme_id'              => 'required|integer',
            'objective_title'       => 'required|string|max:100',
            'objective_description' => 'nullable|string',
            'objective_type_id'     => 'nullable|integer',
            'objective_weight'      => 'nullable|integer|min:0|max:100',
        ]);

        $obj = new StrategicPlanObjective();
        $obj->plan_id              = $planId;
        $obj->theme_id             = $request->theme_id;
        $obj->objective_title      = $request->objective_title;
        $obj->objective_description = $request->objective_description;
        $obj->objective_type_id    = $request->objective_type_id ?? 1;
        $obj->objective_weight     = $request->objective_weight ?? 0;
        $obj->order_no             = StrategicPlanObjective::where('theme_id', $request->theme_id)->max('order_no') + 1;
        $obj->added_by             = Auth::id() ?? 0;
        $obj->added_date           = now();
        $obj->objective_ref        = 'OB-' . strtoupper(uniqid());
        $obj->save();

        return redirect()->route('emp.ext.strategies.show', $planId)
            ->with('success', 'Objective added successfully.');
    }

    // ─────────────────────────────────────────────────────
    //  KPIs
    // ─────────────────────────────────────────────────────

    public function storeKpi(Request $request, $planId)
    {
        $request->validate([
            'theme_id'     => 'required|integer',
            'objective_id' => 'required|integer',
            'kpi_title'    => 'required|string|max:100',
        ]);

        $kpi = new StrategicPlanKpi();
        $kpi->plan_id          = $planId;
        $kpi->theme_id         = $request->theme_id;
        $kpi->objective_id     = $request->objective_id;
        $kpi->kpi_title        = $request->kpi_title;
        $kpi->kpi_description  = $request->kpi_description;
        $kpi->kpi_code         = 'KPI-' . strtoupper(uniqid());
        $kpi->kpi_formula      = $request->kpi_formula;
        $kpi->data_source      = $request->data_source;
        $kpi->kpi_frequncy_id  = $request->kpi_frequncy_id ?? null;
        $kpi->department_id    = $request->department_id ?? 0;
        $kpi->kpi_weight       = $request->kpi_weight ?? 0;
        $kpi->kpi_progress     = 0;
        $kpi->order_no         = StrategicPlanKpi::where('objective_id', $request->objective_id)->max('order_no') + 1;
        $kpi->added_by         = Auth::id() ?? 0;
        $kpi->added_date       = now();
        $kpi->kpi_ref          = 'KP-' . strtoupper(uniqid());
        $kpi->save();

        return redirect()->route('emp.ext.strategies.show', $planId)
            ->with('success', 'KPI added successfully.');
    }

    // ─────────────────────────────────────────────────────
    //  MILESTONES
    // ─────────────────────────────────────────────────────

    public function storeMilestone(Request $request, $planId)
    {
        $request->validate([
            'theme_id'            => 'required|integer',
            'objective_id'        => 'required|integer',
            'kpi_id'              => 'required|integer',
            'milestone_title'     => 'required|string|max:100',
        ]);

        $ms = new StrategicPlanMilestone();
        $ms->plan_id              = $planId;
        $ms->theme_id             = $request->theme_id;
        $ms->objective_id         = $request->objective_id;
        $ms->kpi_id               = $request->kpi_id;
        $ms->milestone_title      = $request->milestone_title;
        $ms->milestone_description = $request->milestone_description;
        $ms->milestone_weight     = $request->milestone_weight ?? 0;
        $ms->order_no             = StrategicPlanMilestone::where('kpi_id', $request->kpi_id)->max('order_no') + 1;
        $ms->added_by             = Auth::id() ?? 0;
        $ms->added_date           = now();
        $ms->milestone_ref        = 'MS-' . strtoupper(uniqid());
        $ms->save();

        return redirect()->route('emp.ext.strategies.show', $planId)
            ->with('success', 'Milestone added successfully.');
    }

    // ─────────────────────────────────────────────────────
    //  EXTERNAL MAPS
    // ─────────────────────────────────────────────────────

    public function storeExternalMap(Request $request, $planId)
    {
        $request->validate([
            'theme_id'          => 'required|integer',
            'objective_id'      => 'required|integer',
            'external_entity_name' => 'required|string|max:200',
        ]);

        $map = new StrategicPlanExternalMap();
        $map->plan_id              = $planId;
        $map->theme_id             = $request->theme_id;
        $map->objective_id         = $request->objective_id;
        $map->external_entity_name = $request->external_entity_name;
        $map->map_description      = $request->map_description;
        $map->start_date           = $request->start_date;
        $map->end_date             = $request->end_date;
        $map->added_by             = Auth::id() ?? 0;
        $map->added_date           = now();
        $map->save();

        return redirect()->route('emp.ext.strategies.show', $planId)
            ->with('success', 'External mapping added successfully.');
    }
}
