<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;

class EmployeeController extends Controller
{
    public function index()
    {
        // Fetch employees with relationships
        $employees = Employee::with(['department', 'designation'])
            ->where('is_hidden', 0)
            ->where('is_deleted', 0)
            ->orderBy('employee_id', 'desc')
            ->paginate(20);

        return view('hr.employees.index', compact('employees'));
    }

    public function show($id)
    {
        $employee = Employee::with(['department', 'designation', 'passwordData'])
            ->where('employee_id', $id)
            ->firstOrFail();

        return view('hr.employees.show', compact('employee'));
    }
    public function create()
    {
        $departments = Department::all();
        $designations = Designation::all();
        return view('hr.employees.create', compact('departments', 'designations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'employee_email' => 'required|email|unique:employees_list,employee_email',
            'department_id' => 'required|exists:employees_list_departments,department_id',
            'designation_id' => 'required|exists:employees_list_designations,designation_id',
            'employee_dob' => 'nullable|date',
            'employee_join_date' => 'nullable|date',
            'employee_type' => 'required|string',
        ]);

        $employee = new Employee();
        $employee->first_name = $request->first_name;
        $employee->last_name = $request->last_name;
        $employee->employee_email = $request->employee_email;
        $employee->department_id = $request->department_id;
        $employee->designation_id = $request->designation_id;
        $employee->employee_dob = $request->employee_dob;
        $employee->employee_join_date = $request->employee_join_date;
        $employee->employee_type = $request->employee_type;
        $employee->employee_code = 'EMP-' . rand(1000, 9999); // Generate a random code for now
        $employee->save();

        return redirect()->route('hr.employees.index')->with('success', 'Employee created successfully.');
    }
}
