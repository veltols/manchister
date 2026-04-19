<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeCred;
use App\Models\SystemLog;
use App\Models\User;
use App\Models\HrLeave;
use App\Models\LeaveType;
use App\Models\Permission;
use App\Models\PermissionStatus;
use App\Models\DisciplinaryAction;
use App\Models\DisciplinaryActionWarning;
use App\Models\DisciplinaryActionStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    public function index()
    {
        $query = Employee::with(['department', 'designation', 'systemUser'])
            ->where('is_hidden', 0)
            ->where('is_deleted', 0);

        // Filter by Active status (1) by default to match view dropdown
        $status = request()->get('status', '1');
        if ($status !== '') {
            $query->whereHas('systemUser', function($q) use ($status) {
                $q->where('is_active', $status);
            });
        }

        $employees = $query->orderBy('employee_id', 'desc')->paginate(20);

        $departments = Department::orderBy('department_name')->where('is_active', 1)->get();

        return view('hr.employees.index', compact('employees', 'departments'));
    }

    public function getData(Request $request)
    {
        $perPage = $request->get('per_page', 20);

        $query = Employee::with(['department', 'designation', 'systemUser'])
            ->where('is_hidden', 0)
            ->where('is_deleted', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('employee_no', 'LIKE', "%{$search}%")
                  ->orWhere('employee_email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('status') && $request->status !== '') {
            $status = $request->status;
            $query->whereHas('systemUser', function($q) use ($status) {
                $q->where('is_active', $status);
            });
        }

        $employees = $query->orderBy('employee_id', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $employees->items(),
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
                'from' => $employees->firstItem(),
                'to' => $employees->lastItem(),
            ]
        ]);
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

        // Fetch lookup data for modals (Include current even if inactive)
        $departments = Department::where('is_active', 1)
            ->orWhere('department_id', $employee->department_id)
            ->orderBy('department_name')
            ->get();
        $designations = Designation::where('is_active', 1)
            ->orWhere('designation_id', $employee->designation_id)
            ->orderBy('designation_name')
            ->get();

        $titles = DB::table('sys_lists')->where('item_category', 'title')->pluck('item_name', 'item_id');
        $genders = DB::table('sys_lists')->where('item_category', 'gender')->pluck('item_name', 'item_id');
        $nationalities = DB::table('sys_countries')->orderBy('country_name')->pluck('country_name', 'country_id');
        $certificates = DB::table('hr_certificates')->orderBy('certificate_name')->pluck('certificate_name', 'certificate_id');

        // Fetch all services and which ones are enabled for this user
        $allServices = \App\Models\EmployeeListService::orderBy('service_id')->get();
        $enabledServiceIds = \App\Models\EmployeeService::where('employee_id', $id)->pluck('service_id')->toArray();

        $leaveTypes = LeaveType::all();
        $permissionStatuses = PermissionStatus::all();
        $warningLevels = DisciplinaryActionWarning::all();
        $daStatuses = DisciplinaryActionStatus::all();

        // Fetch Org Chart Root (exclude deactivated users)
        $activeManagerCheck = fn($q) => $q->whereHas('lineManager', fn($lm) => $lm->whereHas('systemUser', fn($u) => $u->where('is_active', 1)));
        $activeManagerQuery = fn($q) => $q->whereHas('systemUser', fn($u) => $u->where('is_active', 1))->with('designation');
        $activeEmployeesQuery = fn($q) => $q->whereHas('systemUser', fn($u) => $u->where('is_active', 1))->with('designation');
        
        $orgRoot = Department::where('main_department_id', 0)
            ->where('is_active', 1)
            ->where($activeManagerCheck)
            ->with([
                'lineManager'  => $activeManagerQuery,
                'employees'    => $activeEmployeesQuery,
                'children' => fn($q) => $q->where('is_active', 1)->where($activeManagerCheck)->with([
                    'lineManager'  => $activeManagerQuery,
                    'employees'    => $activeEmployeesQuery,
                    'children' => fn($q) => $q->where('is_active', 1)->where($activeManagerCheck)->with([
                        'lineManager'  => $activeManagerQuery,
                        'employees'    => $activeEmployeesQuery,
                        'children' => fn($q) => $q->where('is_active', 1)->where($activeManagerCheck)->with([
                            'lineManager' => $activeManagerQuery,
                            'employees'   => $activeEmployeesQuery,
                        ]),
                    ]),
                ]),
            ])
            ->first();

        return view('hr.employees.show', compact(
            'employee',
            'departments',
            'designations',
            'titles',
            'genders',
            'nationalities',
            'certificates',
            'allServices',
            'enabledServiceIds',
            'leaveTypes',
            'permissionStatuses',
            'warningLevels',
            'daStatuses',
            'orgRoot'
        ));
    }

    public function getLeavesData(Request $request, $id)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->input('search');
        $typeId = $request->input('type_id');
        $statusId = $request->input('status_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = HrLeave::with('type')
            ->where('employee_id', $id)
            ->orderBy('leave_id', 'desc');

        if ($search) {
            $query->where('leave_id', 'LIKE', "%$search%");
        }
        if ($typeId) {
            $query->where('leave_type_id', $typeId);
        }
        if ($statusId) {
            $query->where('leave_status_id', $statusId);
        }
        if ($startDate) {
            $query->whereDate('start_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('end_date', '<=', $endDate);
        }

        $leaves = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $leaves->items(),
            'pagination' => [
                'current_page' => $leaves->currentPage(),
                'last_page' => $leaves->lastPage(),
                'per_page' => $leaves->perPage(),
                'total' => $leaves->total(),
                'from' => $leaves->firstItem(),
                'to' => $leaves->lastItem(),
            ]
        ]);
    }

    public function getPermissionsData(Request $request, $id)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->input('search');
        $statusId = $request->input('status_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Permission::with('status')
            ->where('employee_id', $id)
            ->orderBy('permission_id', 'desc');

        if ($search) {
            $query->where('permission_id', 'LIKE', "%$search%");
        }
        if ($statusId) {
            $query->where('permission_status_id', $statusId);
        }
        if ($startDate) {
            $query->whereDate('start_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('start_date', '<=', $endDate);
        }

        $permissions = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $permissions->items(),
            'pagination' => [
                'current_page' => $permissions->currentPage(),
                'last_page' => $permissions->lastPage(),
                'per_page' => $permissions->perPage(),
                'total' => $permissions->total(),
                'from' => $permissions->firstItem(),
                'to' => $permissions->lastItem(),
            ]
        ]);
    }

    public function getDisciplinaryData(Request $request, $id)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->input('search');
        $warningId = $request->input('warning_id');
        $statusId = $request->input('status_id');
        $date = $request->input('date');

        $query = DisciplinaryAction::with(['type', 'warning', 'status'])
            ->where('employee_id', $id)
            ->orderBy('da_id', 'desc');

        if ($search) {
            $query->where('da_id', 'LIKE', "%$search%");
        }
        if ($warningId) {
            $query->where('da_warning_id', $warningId);
        }
        if ($statusId) {
            $query->where('da_status_id', $statusId);
        }
        if ($date) {
            $query->whereDate('da_date', $date);
        }

        $actions = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $actions->items(),
            'pagination' => [
                'current_page' => $actions->currentPage(),
                'last_page' => $actions->lastPage(),
                'per_page' => $actions->perPage(),
                'total' => $actions->total(),
                'from' => $actions->firstItem(),
                'to' => $actions->lastItem(),
            ]
        ]);
    }

    public function updatePermissions(Request $request, $id)
    {
        $user = Employee::findOrFail($id);
        $user->is_group = $request->has('is_group') ? 1 : 0;
        $user->is_committee = $request->has('is_committee') ? 1 : 0;
        $user->save();

        $this->logAction($id, 'Permissions Updated', "Groups: {$user->is_group}, Committees: {$user->is_committee}. " . $request->log_remark);

        return redirect()->back()->with('success', "Permissions updated successfully.");
    }

    public function updateService(Request $request, $id)
    {
        $request->validate([
            'service_id' => 'required|integer',
            'new_val' => 'required|in:0,1',
        ]);

        $serviceId = (int) $request->service_id;
        $newVal = (int) $request->new_val;

        if ($newVal === 0) {
            // Disable: remove from employees_services
            DB::table('employees_services')
                ->where('employee_id', $id)
                ->where('service_id', $serviceId)
                ->delete();
            $this->logAction($id, 'Service Removed', "Service #{$serviceId} disabled.");
        } else {
            // Enable: insert only if not already present
            $exists = DB::table('employees_services')
                ->where('employee_id', $id)
                ->where('service_id', $serviceId)
                ->exists();
            if (!$exists) {
                DB::table('employees_services')->insert([
                    'employee_id' => $id,
                    'service_id' => $serviceId,
                    'added_by' => Auth::user()->user_id,
                    'added_date' => now()->format('Y-m-d H:i:s'),
                ]);
            }
            $this->logAction($id, 'Service Added', "Service #{$serviceId} enabled.");
        }

        return response()->json(['success' => true, 'message' => 'Service updated.']);
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
            'nationality_id' => 'nullable|integer',
            'certificate_id' => 'nullable|integer',
            'user_type' => 'required|in:emp,hr,eqa',
            'employee_type' => 'nullable|string',
            'leaves_open_balance' => 'required|numeric|min:0',
            'log_remark' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Update Details
            $employee->update($request->except(['log_remark', 'user_type', '_token']));

            // Update System User Role
            if ($employee->systemUser) {
                $employee->systemUser->user_type = $request->user_type;
                $employee->systemUser->save();
            }

            $this->logAction($id, 'Profile Details Updated', $request->log_remark);

            DB::commit();
            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function updateCredentials(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'passport_no'          => 'required|string|max:50',
            'passport_issue_date'  => 'required|date',
            'passport_expiry_date' => 'required|date|after:passport_issue_date',
            'visa_no'              => 'required|string|max:50',
            'visa_issue_date'      => 'required|date',
            'visa_expiry_date'     => 'required|date|after:visa_issue_date',
            'eid_no'               => ['required', 'string', 'regex:/^784-\d{4}-\d{7}-\d{1}$/'],
            'eid_issue_date'       => 'required|date',
            'eid_expiry_date'      => 'required|date|after:eid_issue_date',
            'log_remark'           => 'required|string',
        ], [
            'eid_no.regex' => 'The Emirates ID must follow the format: 784-XXXX-XXXXXXX-X',
            'passport_expiry_date.after' => 'Passport expiry must be after issue date.',
            'visa_expiry_date.after' => 'Visa expiry must be after issue date.',
            'eid_expiry_date.after' => 'Emirates ID expiry must be after issue date.',
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
            'logger_type' => 'hr',
            'logged_by' => auth()->user()->user_id,
            'log_type' => 'int'
        ]);
    }

    public function create()
    {
        $departments = Department::where('is_active', 1)->orderBy('department_name')->get();
        $designations = Designation::where('is_active', 1)->orderBy('designation_name')->get();
        return view('hr.employees.create', compact('departments', 'designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'employee_email' => 'required|email|unique:employees_list,employee_email',
            'department_id' => 'required|exists:employees_list_departments,department_id',
            'designation_id' => 'required|exists:employees_list_designations,designation_id',
            'employee_dob' => 'nullable|date',
            'employee_join_date' => 'nullable|date',
            'employee_type' => 'required|string',
            'user_type' => 'required|in:emp,hr,eqa',
            'password' => [
                'required',
                'string',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        DB::beginTransaction();
        try {
            // 1. Create Employee
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
            $employee->employee_no = rand(10000, 99999); // Temporary or generated IQC ID
            $employee->is_pass = 1;
            $employee->emp_status_id = 1;
            $employee->save();

            // 2. Create Password
            $pass = new \App\Models\EmployeePass();
            $pass->employee_id = $employee->employee_id;
            $pass->pass_value = Hash::make($request->password);
            $pass->is_active = 1;
            $pass->save();

            // 3. System User
            $sysUser = new User();
            $sysUser->user_id = $employee->employee_id;
            $sysUser->user_email = $request->employee_email;
            $sysUser->user_type = $request->user_type;
            $sysUser->int_ext = 'int';
            $sysUser->user_family = 'employees_list';
            $sysUser->user_theme_id = 7;
            $sysUser->save();

            // 4. Creds
            $cred = new \App\Models\EmployeeCred();
            $cred->employee_id = $employee->employee_id;
            $cred->save();

            // 5. Handle Line Manager Assignment
            if ($request->is_line_manager == 1) {
                Department::where('department_id', $request->department_id)
                    ->update(['line_manager_id' => $employee->employee_id]);
            }

            $this->logAction($employee->employee_id, 'Employee Created', "Full employee profile and credentials created via HR and assigned to department [{$employee->department_id}].");

            DB::commit();
            return redirect()->route('hr.employees.index')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create employee: ' . $e->getMessage())->withInput();
        }
    }
}
