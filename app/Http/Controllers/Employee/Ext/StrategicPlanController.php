<?php

namespace App\Http\Controllers\Employee\Ext;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StrategicPlan;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class StrategicPlanController extends Controller
{
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
            'plan_title' => 'required|string|max:255',
            'plan_from' => 'required|date',
            'plan_to' => 'required|date',
            'plan_level' => 'required|integer',
            'plan_vision' => 'nullable|string',
            'plan_mission' => 'nullable|string',
            'plan_values' => 'nullable|string',
            'plan_period' => 'nullable|string',
        ]);

        $plan = new StrategicPlan();
        $plan->plan_title = $request->plan_title;
        $plan->plan_from = $request->plan_from;
        $plan->plan_to = $request->plan_to;
        $plan->plan_period = $request->plan_period;
        $plan->plan_level = $request->plan_level;
        $plan->plan_vision = $request->plan_vision;
        $plan->plan_mission = $request->plan_mission;
        $plan->plan_values = $request->plan_values;

        $plan->plan_status_id = 1; // Draft
        $plan->added_by = Auth::id() ?? 0;
        $plan->added_date = now();
        $plan->plan_ref = 'SP-' . strtoupper(uniqid());

        $plan->save();

        return redirect()->route('emp.ext.strategies.index')->with('success', 'Strategic Plan created successfully.');
    }

    public function show($id)
    {
        $plan = StrategicPlan::with(['department', 'themes'])->findOrFail($id);
        return view('emp.ext.strategies.show', compact('plan'));
    }
}
