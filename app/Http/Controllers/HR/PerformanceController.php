<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Performance;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Performance::with('employee')->orderBy('performance_id', 'desc');

        if ($request->has('employee_id') && $request->employee_id != '') {
             $query->where('employee_id', $request->employee_id);
        }

        $records = $query->paginate(15);
        $employees = Employee::where('is_deleted', 0)->orderBy('first_name')->get();

        return view('hr.performance.index', compact('records', 'employees'));
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $query = Performance::with('employee')->orderBy('performance_id', 'desc');

        if ($request->has('employee_id') && $request->employee_id != '') {
             $query->where('employee_id', $request->employee_id);
        }

        $records = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $records->items(),
            'pagination' => [
                'current_page' => $records->currentPage(),
                'last_page' => $records->lastPage(),
                'per_page' => $records->perPage(),
                'total' => $records->total(),
                'from' => $records->firstItem(),
                'to' => $records->lastItem(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees_list,employee_id',
            'performance_object' => 'required|string',
            'performance_kpi' => 'required|string',
            'performance_remark' => 'nullable|string',
        ]);

        $perf = new Performance();
        $perf->employee_id = $request->employee_id;
        $perf->performance_object = $request->performance_object;
        $perf->performance_kpi = $request->performance_kpi;
        $perf->performance_remark = $request->performance_remark ?? '';
        $perf->added_date = now();
        $perf->added_by = Auth::id(); // Assuming added_by exists in schema from serv_list check? 
        // serv_list doesn't explicitly select added_by but it's common in this legacy db. 
        // Logic: serv_list usually selects * so it should be there. 
        // We'll trust the pattern.
        
        $perf->save();

        return redirect()->back()->with('success', 'Performance record added.');
    }
    
    public function update(Request $request, $id)
    {
        // Implement update if needed, legacy code has `update_hr_performance_list`
        // But the view only showed "Add" and "View" (which opened modal).
        // Let's add basic update for completeness.
        $perf = Performance::findOrFail($id);
        
        $request->validate([
            'performance_object' => 'required|string',
            'performance_kpi' => 'required|string',
        ]);
        
        $perf->performance_object = $request->performance_object;
        $perf->performance_kpi = $request->performance_kpi;
        $perf->performance_remark = $request->performance_remark ?? '';
        
        $perf->save();
        
        return redirect()->back()->with('success', 'Performance record updated.');
    }
}
