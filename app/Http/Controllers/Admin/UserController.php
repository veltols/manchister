<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\EmployeePass;
use App\Models\User; // Legacy users_list model if exists or create new one
use App\Models\EmployeeCred; // Create this model
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        // Fetch users (which are employees with login access commonly)
        // Adjust query based on how 'Users' are distinguished in legacy. 
        // Legacy: users_list has user_id = employee_id.
        $users = DB::table('users_list')
            ->join('employees_list', 'users_list.user_id', '=', 'employees_list.employee_id')
            ->select('employees_list.*', 'users_list.user_id', 'users_list.user_type', 'users_list.user_email as login_email')
            ->orderBy('users_list.record_id', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $departments = Department::all();
        // Assuming we might need titles/genders etc later, but for now matching legacy 'serv_new.php'
        return view('admin.users.create', compact('departments'));
    }

    public function store(Request $request)
    {
        Log::debug('Store method reached', $request->all());
        $request->validate([
            'employee_no' => 'required|string|unique:employees_list,employee_no',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees_list,employee_email',
            'password' => 'required|string|min:6',
            'department_id' => 'required|exists:employees_list_departments,department_id',
            'user_type' => 'required|in:emp,hr,admin_hr,sys_admin,root,eqa',
        ]);

        DB::beginTransaction();

        try {
            // 1. Create Employee
            $employee = new Employee();
            $employee->employee_no = $request->employee_no;
            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->employee_email = $request->email;
            $employee->department_id = $request->department_id;

            // Generate Code: Initials of first/last name
            $initials = strtoupper(substr($request->first_name, 0, 1) . substr($request->last_name, 0, 1));
            $employee->employee_code = $initials; // Legacy logic seems to use initials?

            // Defaults as per legacy or nullable
            $employee->title_id = 1;
            $employee->gender_id = 1;
            $employee->nationality_id = 224;
            $employee->timezone_id = 263;
            $employee->is_deleted = 0;
            $employee->is_hidden = 0;

            // Find a valid designation if none provided (legacy default)
            $designation = \App\Models\Designation::first();
            $employee->designation_id = $designation ? $designation->designation_id : 1;

            // Other required fields found in DB check
            $employee->employee_type = 'local';
            $employee->employee_picture = 'user.png';
            $employee->certificate_id = 1;
            $employee->leaves_open_balance = 0;
            $employee->allowed_permission_hours = 8;
            $employee->permission_hours_balance = 0;
            $employee->is_group = 0;
            $employee->is_committee = 0;
            $employee->is_new = 1;
            $employee->is_pass = 0;
            $employee->emp_status_id = 1;

            $employee->save();
            $employeeId = $employee->employee_id;

            // 2. Create Password
            // Laravel Hash::make is standard and compatible with default auth
            $hashedPassword = Hash::make($request->password);

            DB::table('employees_list_pass')->insert([
                'employee_id' => $employeeId,
                'pass_value' => $hashedPassword,
                'is_active' => 1
            ]);

            // 3. Create Creds
            DB::table('employees_list_creds')->insert([
                'employee_id' => $employeeId
            ]);

            // 4. Create User Entry
            $userType = $request->user_type ?? 'emp';

            DB::table('users_list')->insert([
                'user_id' => $employeeId,
                'user_email' => $request->email,
                'user_type' => $userType,
                'int_ext' => 'int',
                'user_family' => 'employees_list',
                'is_active' => 1,
                'user_lang' => 'en',
                'user_theme_id' => 1
            ]);

            // 5. System Log
            Log::info("User successfully created: " . $request->email . " User ID: " . $employeeId);

            DB::table('sys_logs')->insert([
                'related_table' => 'employees_list',
                'related_id' => $employeeId,
                'log_date' => now(),
                'log_action' => 'Employee_Added',
                'log_remark' => 'Created via Admin Panel',
                'logger_type' => 'employees_list',
                'logged_by' => auth()->id() ?? 0,
                'log_type' => 'int'
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("User creation failed. Error: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            return back()->withInput()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = DB::table('users_list')
            ->join('employees_list', 'users_list.user_id', '=', 'employees_list.employee_id')
            ->join('employees_list_departments', 'employees_list.department_id', '=', 'employees_list_departments.department_id')
            ->where('users_list.user_id', $id) // Assuming ID passed is user_id/employee_id not record_id
            ->select('employees_list.*', 'users_list.*', 'employees_list_departments.department_name')
            ->first();

        if (!$user) {
            // Try searching by record_id just in case
            $user = DB::table('users_list')
                ->join('employees_list', 'users_list.user_id', '=', 'employees_list.employee_id')
                ->join('employees_list_departments', 'employees_list.department_id', '=', 'employees_list_departments.department_id')
                ->where('users_list.record_id', $id)
                ->select('employees_list.*', 'users_list.*', 'employees_list_departments.department_name')
                ->first();
        }

        if (!$user)
            abort(404);

        return view('admin.users.show', compact('user'));
    }
}
