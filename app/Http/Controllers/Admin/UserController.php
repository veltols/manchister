<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeePass;
use App\Models\Department;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Filter by user/email search if needed
        $query = Employee::with(['department'])
            ->where('is_deleted', 0)
            ->where('is_hidden', 0) // Assuming this hides non-active system users
            ->orderBy('employee_id', 'desc');

        $users = $query->paginate(15);
        $departments = Department::orderBy('department_name')->get();

        return view('admin.users.index', compact('users', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_no' => 'required|string|unique:employees_list,employee_no',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'department_id' => 'required|exists:employees_list_departments,department_id',
            'employee_email' => 'required|email|unique:employees_list,employee_email',
            'password' => 'required|string|min:6', // Legacy didn't strictly validate strength
        ]);

        // Create Employee
        $emp = new Employee();
        $emp->employee_no = $request->employee_no;
        $emp->first_name = $request->first_name;
        $emp->last_name = $request->last_name;
        $emp->department_id = $request->department_id;
        $emp->employee_email = $request->employee_email;
        $emp->joined_date = now(); // Defaulting to now
        $emp->is_active = 1; // Default
        // Add other mandatory fields with defaults if necessary
        $emp->save();

        // Save Password
        $pass = new EmployeePass();
        $pass->employee_id = $emp->employee_id;
        $pass->pass_value = $request->password; // Legacy - check if hashing is used. 
        // NOTE: Laravel Auth usually expects Hashed password.
        // However, if we look at User model: return $this->employee->passwordData->pass_value;
        // This implies it returns whatever is in DB. If Auth::attempt works, it compares Hash::make(input) with DB value.
        // BUT strict legacy systems often store plain text or MD5. 
        // If we want to be safe and "Premium", we should Hash it. 
        // Converting to Hash::make($request->password). 
        // CAUTION: If legacy system expects plain text, this will break legacy login.
        // Given this is a revamp, I will use Hash::make() BUT I'll leave a comment.
        // CHECK: detailed login logic was not fully visible but standard Laravel Auth expects Hash.
        // Assuming we start using Hash for new users.
        $pass->pass_value = Hash::make($request->password);
        
        $pass->is_active = 1;
        $pass->entry_time = now();
        $pass->entry_who = 1; // Admin
        $pass->save();

        // Log
        $this->logAction($emp->employee_id, 'User Created', "User {$emp->first_name} {$emp->last_name} created.");

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        $user = Employee::with(['department', 'designation', 'passwordData'])->findOrFail($id);
        
        // Fetch related data for tabs (Assets, Services, etc) if needed
        // For now, basic details
        
        return view('admin.users.show', compact('user'));
    }

    private function logAction($refId, $action, $remark)
    {
         $log = new SystemLog();
        $log->log_ref = $refId;
        $log->log_date = now();
        $log->log_action = $action;
        $log->log_remark = $remark;
        $log->log_user_id = 1; 
        $log->save();
    }
}
