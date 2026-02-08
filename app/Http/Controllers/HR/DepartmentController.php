<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with(['mainDepartment', 'lineManager'])
            ->orderBy('department_id', 'desc')
            ->paginate(15);

        $employees = Employee::where('is_deleted', 0)->where('is_hidden', 0)->orderBy('first_name')->get();
        // For parent department selection, exclude those that shouldn't be parents if needed, or just list clear ones
        $allDepartments = Department::orderBy('department_name')->get();

        return view('hr.departments.index', compact('departments', 'employees', 'allDepartments'));
    }

    public function getData(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 15);
        
        $departments = Department::with(['mainDepartment', 'lineManager'])
            ->orderBy('department_id', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $departments->items(),
            'pagination' => [
                'current_page' => $departments->currentPage(),
                'last_page' => $departments->lastPage(),
                'per_page' => $departments->perPage(),
                'total' => $departments->total(),
                'from' => $departments->firstItem(),
                'to' => $departments->lastItem(),
            ]
        ]);
    }

    public function orgChart()
    {
        $departments = Department::orderBy('department_name')->get();
        return view('hr.departments.chart', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_code' => 'required|string|max:50',
            'department_name' => 'required|string|max:255',
            'main_department_id' => 'nullable|integer',
            'line_manager_id' => 'nullable|exists:employees_list,employee_id',
            'log_remark' => 'nullable|string',
        ]);

        $dept = new Department();
        $dept->department_code = $request->department_code;
        $dept->department_name = $request->department_name;
        $dept->main_department_id = $request->main_department_id == 0 ? 0 : $request->main_department_id;
        $dept->line_manager_id = $request->line_manager_id;
        $dept->user_type = 'emp'; // Default from legacy logic
        $dept->save();

        $this->logAction($dept->department_id, 'Department_Added', $request->log_remark ?? '---');

        return redirect()->back()->with('success', 'Department created successfully.');
    }

    public function update(Request $request, $id)
    {
        $dept = Department::findOrFail($id);

        $request->validate([
            'department_code' => 'required|string|max:50',
            'department_name' => 'required|string|max:255',
            'main_department_id' => 'nullable|integer',
            'line_manager_id' => 'nullable|exists:employees_list,employee_id',
            'log_remark' => 'required|string',
        ]);

        $dept->department_code = $request->department_code;
        $dept->department_name = $request->department_name;
        $dept->main_department_id = $request->main_department_id == 0 ? 0 : $request->main_department_id;
        $dept->line_manager_id = $request->line_manager_id;
        $dept->save();

        $this->logAction($dept->department_id, 'Department_Updated', $request->log_remark);

        return redirect()->back()->with('success', 'Department updated successfully.');
    }

    private function logAction($id, $action, $remark)
    {
        \App\Models\SystemLog::create([
            'related_table' => 'employees_list_departments',
            'related_id' => $id,
            'log_date' => now(),
            'log_action' => $action,
            'log_remark' => $remark,
            'logger_type' => 'employees_list',
            'logged_by' => Auth::id() ?? 1,
            'log_type' => 'int'
        ]);
    }
}
