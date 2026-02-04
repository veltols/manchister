<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeCred;
use App\Models\SystemLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['department', 'designation'])
            ->where('is_hidden', 0)
            ->where('is_deleted', 0)
            ->orderBy('employee_id', 'desc')
            ->paginate(20);

        return view('hr.employees.index', compact('employees'));
    }

    public function show($id)
    {
        $employee = Employee::with([
            'department',
            'designation',
            'credentials',
            'passwordData',
            'leaves.type',
            'permissions.status',
            'attendance',
            'disciplinaryActions.type',
            'disciplinaryActions.status',
            'performance',
            'exitInterviews',
            'logs'
        ])
            ->where('employee_id', $id)
            ->firstOrFail();

        // Fetch lookup data for modals
        $departments = Department::orderBy('department_name')->get();
        $designations = Designation::orderBy('designation_name')->get();

        $titles = DB::table('sys_lists')->where('item_category', 'title')->pluck('item_name', 'item_id');
        $genders = DB::table('sys_lists')->where('item_category', 'gender')->pluck('item_name', 'item_id');
        $nationalities = DB::table('sys_countries')->orderBy('country_name')->pluck('country_name', 'country_id');
        $certificates = DB::table('hr_certificates')->orderBy('certificate_name')->pluck('certificate_name', 'certificate_id');

        return view('hr.employees.show', compact(
            'employee',
            'departments',
            'designations',
            'titles',
            'genders',
            'nationalities',
            'certificates'
        ));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'employee_dob' => 'required|date',
            'employee_join_date' => 'required|date',
            'department_id' => 'required|exists:employees_list_departments,department_id',
            'designation_id' => 'required|exists:employees_list_designations,designation_id',
            'log_remark' => 'required|string',
        ]);

        $employee->update($request->except(['log_remark', '_token']));

        $this->logAction($employee->employee_id, 'Employee_Updated', $request->log_remark);

        return redirect()->back()->with('success', 'Employee profile updated successfully.');
    }

    public function updateCredentials(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'log_remark' => 'required|string',
        ]);

        $creds = EmployeeCred::updateOrCreate(
            ['employee_id' => $id],
            $request->except(['log_remark', '_token'])
        );

        $this->logAction($employee->employee_id, 'Employee_Credentials_Updated', $request->log_remark);

        return redirect()->back()->with('success', 'Employee credentials updated successfully.');
    }

    private function logAction($id, $action, $remark)
    {
        SystemLog::create([
            'related_table' => 'employees_list',
            'related_id' => $id,
            'log_date' => now(),
            'log_action' => $action,
            'log_remark' => $remark,
            'logger_type' => 'employees_list',
            'logged_by' => Auth::id() ?? 1,
            'log_type' => 'int'
        ]);
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
        $employee->employee_code = 'EMP-' . rand(1000, 9999);
        $employee->save();

        return redirect()->route('hr.employees.index')->with('success', 'Employee created successfully.');
    }
}
