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
            $employee->title_id = 0;
            $employee->gender_id = 0;
            $employee->nationality_id = 0;
            $employee->timezone_id = 0;
            $employee->is_deleted = 0;
            $employee->is_hidden = 0;
            $employee->designation_id = 0; // Fix: Strict mode requires value

            $employee->save();
            $employeeId = $employee->employee_id;

            // 2. Create Password
            // Legacy uses Bcrypt cost 12. Laravel default is often Bcrypt.
            // Explicitly matching legacy config:
            $hashedPassword = password_hash($request->password, PASSWORD_BCRYPT, ["cost" => 12]);

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
            // Use selected user_type from form
            $userType = $request->user_type ?? 'emp'; // Default to emp if not set

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
            \Log::info("User successfully created: " . $request->email . " User ID: " . $employeeId);

            DB::table('sys_logs')->insert([
                'related_table' => 'employees_list',
                'related_id' => $employeeId,
                'log_date' => now(), // Laravel now()
                'log_action' => 'Employee_Added',
                'log_remark' => '---',
                'logger_type' => 'employees_list',
                'logged_by' => auth()->id() ?? 0, // Current admin ID
                'log_type' => 'int'
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("User creation failed: " . $e->getMessage());
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
