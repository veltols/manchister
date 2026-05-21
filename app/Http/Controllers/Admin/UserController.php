<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeePass;
use App\Models\Department;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\EmployeeListService;
use App\Models\EmployeeService;
use App\Models\Designation;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'systemUser'])
            ->where('is_deleted', 0)
            ->where('is_hidden', 0);

        $search = $request->get('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('employee_no', 'LIKE', "%{$search}%")
                  ->orWhere('employee_email', 'LIKE', "%{$search}%")
                  ->orWhereHas('systemUser', function($sq) use ($search) {
                      $sq->where('user_type', 'LIKE', "%{$search}%")
                        ->orWhere('user_email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by Active status (1) by default to match view dropdown
        $status = $request->get('status', '1');
        if ($status !== '') {
            $query->whereHas('systemUser', function($q) use ($status) {
                $q->where('is_active', $status);
            });
        }

        $users = $query->orderBy('employee_id', 'desc')->paginate(15);
        $departments = Department::orderBy('department_name')->where('is_active', 1)->get();

        return view('admin.users.index', compact('users', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_no' => 'required|string|unique:employees_list,employee_no',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'department_id' => 'required|exists:employees_list_departments,department_id',
            'user_type' => 'required|in:emp,hr,eqa',
            'employee_email' => 'nullable|unique:employees_list,employee_email',
            'employee_type' => 'nullable|string',
            'probation_type' => 'nullable|in:initial,extended,completed',
            'probation_end_date' => 'nullable|date',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        DB::beginTransaction();
        try {
            // 1. Create Employee
            $emp = new Employee();
            $emp->employee_no = $request->employee_no;
            $emp->first_name = $request->first_name;
            $emp->last_name = $request->last_name;
            $emp->department_id = $request->department_id;
            $emp->employee_email = $request->employee_email;
            $emp->employee_join_date = now();
            $emp->employee_code = 'EMP-' . rand(1000, 9999);
            $emp->designation_id = 0;
            $emp->is_pass = 1;
            $emp->employee_type = $request->employee_type ?? 'full_time';
            $emp->probation_type = $request->probation_type ?: null;
            $emp->probation_end_date = $request->probation_end_date ?: null;
            $emp->emp_status_id = 1; // Assuming 1 is Active
            $emp->save();

            // 2. Save Password
            $pass = new EmployeePass();
            $pass->employee_id = $emp->employee_id;
            $pass->pass_value = Hash::make($request->password);
            $pass->is_active = 1;
            $pass->save();

            // 3. User Type from Request
            $userType = $request->user_type;

            // 4. Create Credentials Record
            $cred = new \App\Models\EmployeeCred();
            $cred->employee_id = $emp->employee_id;
            $cred->save();

            // 5. Create System User Record (users_list)
            $sysUser = new \App\Models\User();
            $sysUser->user_id = $emp->employee_id;
            $sysUser->user_email = $request->employee_email;
            $sysUser->user_type = $userType;
            $sysUser->int_ext = 'int';
            $sysUser->user_family = 'employees_list';
            $sysUser->user_theme_id = 7;
            
            // Handle GM logic
            if ($request->is_gm == 1) {
                // Clear other GMs first
                \App\Models\User::where('is_gm', 1)->update(['is_gm' => 0]);
                $sysUser->is_gm = 1;
            } else {
                $sysUser->is_gm = 0;
            }

            $sysUser->save();

            // 6. Handle Line Manager Assignment
            $lmMsg = '';
            if ($request->is_line_manager == 1) {
                $dept = Department::find($request->department_id);
                if ($dept && $dept->line_manager_id != 0) {
                    $oldLm = Employee::find($dept->line_manager_id);
                    $oldName = $oldLm ? $oldLm->full_name : 'Previous Manager';
                    $lmMsg = " (Note: {$oldName} was replaced as Line Manager)";
                }
                Department::where('department_id', $request->department_id)
                    ->update(['line_manager_id' => $emp->employee_id]);
            }

            // Log
            $this->logAction($emp->employee_id, 'User Created', "User [{$emp->full_name}] created and assigned to department [{$emp->department_id}]. " . $lmMsg);

            DB::commit();
            return redirect()->back()->with('success', 'User created successfully.' . $lmMsg);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = Employee::with(['department', 'designation', 'passwordData', 'systemUser'])->findOrFail($id);

        // Fetch assigned assets
        $assets = Asset::with(['category'])
            ->where('assigned_to', $id)
            ->get();

        // Fetch activity logs
        $logs = SystemLog::where('related_table', 'employees_list')
            ->where('related_id', $id)
            ->orderBy('log_date', 'desc')
            ->get();

        // Fetch available assets for assignment
        $availableAssets = Asset::where('status_id', 1) // Assuming 1 is "Available"
            ->where('assigned_to', 0)
            ->get();

        // Lookups for edit modal
        $departments = Department::where('is_active', 1)
            ->orWhere('department_id', $user->department_id)
            ->orderBy('department_name')
            ->get();
        $designations = Designation::where('is_active', 1)
            ->orWhere('designation_id', $user->designation_id)
            ->orderBy('designation_name')
            ->get();
        
        $titles = DB::table('sys_lists')->where('item_category', 'title')->pluck('item_name', 'item_id');
        $genders = DB::table('sys_lists')->where('item_category', 'gender')->pluck('item_name', 'item_id');
        $nationalities = DB::table('sys_countries')->orderBy('country_name')->pluck('country_name', 'country_id');
        $certificates = DB::table('hr_certificates')->orderBy('certificate_name')->pluck('certificate_name', 'certificate_id');

        // Fetch all services and which ones are enabled for this user
        $allServices = EmployeeListService::orderBy('service_id')->get();
        $enabledServiceIds = EmployeeService::where('employee_id', $id)->pluck('service_id')->toArray();

        // Org Chart Data - User Related Tree Only
        $activeManagerCheck = fn($q) => $q->whereHas('lineManager', fn($lm) => $lm->whereHas('systemUser', fn($u) => $u->where('is_active', 1)));
        $activeManagerQuery  = fn($q) => $q->whereHas('systemUser', fn($u) => $u->where('is_active', 1))->with('designation');
        $activeEmployeesQuery = fn($q) => $q->whereHas('systemUser', fn($u) => $u->where('is_active', 1))->with('designation');
        
        $orgRoot = Department::where('department_id', $user->department_id)
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

        // Calculate related department IDs (current, ancestors, descendants)
        $relatedDeptIds = [];
        if ($user->department_id) {
            $relatedDeptIds[] = (int)$user->department_id;
            
            // Ancestors (parents, grandparents, etc.)
            $currentDeptId = $user->department_id;
            while ($currentDeptId) {
                $parentDept = Department::find($currentDeptId);
                if ($parentDept && $parentDept->main_department_id) {
                    $relatedDeptIds[] = (int)$parentDept->main_department_id;
                    $currentDeptId = $parentDept->main_department_id;
                } else {
                    break;
                }
            }
            
            // Descendants (children, grandchildren, etc.)
            $fetchDescendants = function($deptId) use (&$relatedDeptIds, &$fetchDescendants) {
                $childrenIds = Department::where('main_department_id', $deptId)->pluck('department_id')->toArray();
                foreach ($childrenIds as $childId) {
                    $relatedDeptIds[] = (int)$childId;
                    $fetchDescendants($childId);
                }
            };
            $fetchDescendants($user->department_id);
        }
        $relatedDeptIds = array_unique($relatedDeptIds);

        return view('admin.users.show', compact(
            'user', 'assets', 'logs', 'availableAssets', 
            'allServices', 'enabledServiceIds', 'departments', 
            'designations', 'titles', 'genders', 'nationalities', 
            'certificates', 'orgRoot', 'relatedDeptIds'
        ));
    }

    public function update(Request $request, $id)
    {
        $user = Employee::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'employee_dob' => 'nullable|date',
            'employee_join_date' => 'nullable|date',
            'department_id' => 'required|exists:employees_list_departments,department_id',
            'designation_id' => 'required|exists:employees_list_designations,designation_id',
            'nationality_id' => 'nullable|integer',
            'certificate_id' => 'nullable|integer',
            'user_type' => 'required|in:emp,hr,eqa',
            'employee_type' => 'nullable|string',
            'probation_type' => 'nullable|in:initial,extended,completed',
            'probation_end_date' => 'nullable|date',
            'log_remark' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Update Employee details
            $user->update($request->except(['log_remark', 'user_type', '_token', 'is_gm', 'is_line_manager']));

            // Update System User Role + GM flag
            if ($user->systemUser) {
                $user->systemUser->user_type = $request->user_type;

                $user->systemUser->save();
            }

            // Handle Line Manager Assignment
            $lmMsg = '';
            if ($request->is_line_manager == 1) {
                $dept = Department::find($request->department_id);
                if ($dept && $dept->line_manager_id != $user->employee_id && $dept->line_manager_id != 0) {
                    $oldLm = Employee::find($dept->line_manager_id);
                    $oldName = $oldLm ? $oldLm->full_name : 'Previous Manager';
                    $lmMsg = " (Note: {$oldName} was replaced as Line Manager)";
                }

                // Remove this user from any other department they might manage
                Department::where('line_manager_id', $user->employee_id)->update(['line_manager_id' => 0]);
                
                // Assign them to the current department
                Department::where('department_id', $request->department_id)
                    ->update(['line_manager_id' => $user->employee_id]);
            } else {
                // Remove as LM if they were the LM of this department
                Department::where('department_id', $request->department_id)
                    ->where('line_manager_id', $user->employee_id)
                    ->update(['line_manager_id' => 0]);
            }

            $this->logAction($user->employee_id, 'User Profile Updated', $request->log_remark . $lmMsg);

            DB::commit();
            return redirect()->back()->with('success', 'User profile updated successfully.' . $lmMsg);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $systemUser = \App\Models\User::where('user_id', $id)->first();

        if ($systemUser) {
            $newStatus = $request->status; // 1 = Activate, 0 = Deactivate

            $logRemark = "Status changed to " . ($newStatus ? 'Active' : 'Inactive');
            $action = $newStatus ? 'User Activated' : 'User Deactivated';

            // Validate mandatory remark and optional attachment
            $request->validate([
                'log_remark' => 'required|string',
                'log_attachment' => 'nullable|file|max:10240'
            ]);
            $logRemark = $request->log_remark;

            // Handle Attachment
            if ($request->hasFile('log_attachment')) {
                $file = $request->file('log_attachment');
                $filename = ($newStatus == 1 ? 'activate_' : 'deactivate_') . time() . '_' . $id . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/admin_logs'), $filename);

                // Append attachment link to remark
                $logRemark .= "\n[Attachment: uploads/admin_logs/$filename]";
            }

            $systemUser->is_active = $newStatus;
            $systemUser->save();

            // Sync with HR employee status
            if ($systemUser->employee) {
                $systemUser->employee->emp_status_id = $newStatus ? 1 : 2; // Assuming 2 is inactive/suspended
                $systemUser->employee->save();
            }

            $this->logAction($id, $action, $logRemark);

            return redirect()->back()->with('success', "User status updated successfully.");
        }

        return redirect()->back()->with('error', "System user record not found.");
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'log_remark' => 'required|string'
        ]);

        $user = Employee::findOrFail($id);
        
        // 1. Deactivate all existing passwords for this employee
        EmployeePass::where('employee_id', $id)->update(['is_active' => 0]);
        
        // 2. Insert new password record (keep history)
        $pass = new EmployeePass();
        $pass->employee_id = $id;
        $pass->pass_value = Hash::make($request->password);
        $pass->is_active = 1;
        $pass->save();
        
        // 3. Mark employee as having a password set just in case
        $user->is_pass = 1;
        $user->save();

        $this->logAction($id, 'Password Reset', $request->log_remark);

        return redirect()->back()->with('success', "Password reset successfully.");
    }

    public function updatePermissions(Request $request, $id)
    {
        $user = Employee::findOrFail($id);
        $user->is_group     = $request->has('is_group')     ? 1 : 0;
        $user->is_committee = $request->has('is_committee') ? 1 : 0;
        $user->save();

        // Update is_gm and is_liaison on the users_list record
        if ($user->systemUser) {
            $newIsGm = $request->has('is_gm') ? 1 : 0;

            if ($newIsGm === 1) {
                // Clear other GMs first
                \App\Models\User::where('is_gm', 1)
                    ->where('user_id', '!=', $user->employee_id)
                    ->update(['is_gm' => 0]);
            }

            $user->systemUser->is_gm = $newIsGm;
            $user->systemUser->is_liaison = $request->has('is_liaison') ? 1 : 0;
            $user->systemUser->save();
        }

        $isGm      = $request->has('is_gm')      ? 'Yes' : 'No';
        $isLiaison = $request->has('is_liaison')  ? 'Yes' : 'No';
        $this->logAction($id, 'Permissions Updated',
            "Groups: {$user->is_group}, Committees: {$user->is_committee}, GM: {$isGm}, Liaison: {$isLiaison}. " . $request->log_remark);

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

    public function assignAsset(Request $request, $id)
    {
        $request->validate([
            'asset_id' => 'required|exists:z_assets_list,asset_id',
            'log_remark' => 'required|string'
        ]);

        $asset = Asset::findOrFail($request->asset_id);
        $asset->assigned_to = $id;
        $asset->assigned_date = now();
        $asset->status_id = 2; // Assuming 2 is "Assigned"
        $asset->save();

        $this->logAction($id, 'Asset Assigned', "Asset #{$asset->asset_ref} assigned. " . $request->log_remark);

        return redirect()->back()->with('success', "Asset assigned successfully.");
    }

    public function revokeAsset(Request $request, $id)
    {
        $request->validate([
            'asset_id' => 'required|exists:z_assets_list,asset_id',
            'log_remark' => 'required|string'
        ]);

        $asset = Asset::findOrFail($request->asset_id);
        $asset->assigned_to = 0;
        $asset->status_id = 1; // Available
        $asset->save();

        $this->logAction($id, 'Asset Revoked', "Asset #{$asset->asset_ref} revoked. " . $request->log_remark);

        return redirect()->back()->with('success', "Asset revoked successfully.");
    }

    public function updateLoginId(Request $request, $id)
    {
        $request->validate([
            'new_email' => 'required|string|unique:employees_list,employee_email,'.$id.',employee_id|unique:users_list,user_email,'.$id.',user_id',
            'log_remark' => 'required|string'
        ]);

        $user = Employee::findOrFail($id);
        $systemUser = \App\Models\User::where('user_id', $id)->first();

        $oldEmail = $user->employee_email;
        $newEmail = $request->new_email;

        DB::beginTransaction();
        try {
            // Update Employee
            $user->employee_email = $newEmail;
            $user->save();

            // Update System User
            if ($systemUser) {
                $systemUser->user_email = $newEmail;
                $systemUser->save();
            }

            $this->logAction($id, 'Login ID Updated', "Updated from {$oldEmail} to {$newEmail}. Reason: " . $request->log_remark);

            DB::commit();
            return redirect()->back()->with('success', "Login ID updated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Failed to update Login ID: " . $e->getMessage());
        }
    }

    public function toggleFeedback($id)
    {
        $systemUser = \App\Models\User::where('user_id', $id)->first();

        if (!$systemUser) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $systemUser->feedback_enabled = $systemUser->feedback_enabled ? 0 : 1;
        $systemUser->save();

        $state  = $systemUser->feedback_enabled ? 'enabled' : 'disabled';
        $action = $systemUser->feedback_enabled ? 'Feedback Enabled' : 'Feedback Disabled';
        $this->logAction($id, $action, "Feedback access {$state} by admin.");

        return response()->json([
            'success'          => true,
            'feedback_enabled' => $systemUser->feedback_enabled,
            'message'          => "Feedback {$state} for this user.",
        ]);
    }

    public function toggleLm($id)
    {
        $employee = Employee::findOrFail($id);
        $deptId = $employee->department_id;

        if (!$deptId) {
            return response()->json(['success' => false, 'message' => 'User has no assigned department.'], 400);
        }

        $department = Department::find($deptId);
        if (!$department) {
            return response()->json(['success' => false, 'message' => 'Department not found.'], 404);
        }

        $isCurrentlyLm = (int)$department->line_manager_id === (int)$id;
        $oldLmId = $department->line_manager_id;
        
        if ($isCurrentlyLm) {
            // Remove as LM
            $department->line_manager_id = 0;
            $department->save();
            $state = 'removed from Line Manager role';
            $action = 'Line Manager Removed';
            $message = "Line Manager role removed for {$department->department_name}.";
        } else {
            // Designate as LM (replaces any existing LM for this dept)
            $department->line_manager_id = $id;
            $department->save();
            $state = 'designated as Line Manager';
            $action = 'Line Manager Designated';
            
            $message = "User designated as Line Manager for {$department->department_name}.";
            if ($oldLmId && (int)$oldLmId !== (int)$id) {
                $oldLm = Employee::find($oldLmId);
                $oldName = $oldLm ? $oldLm->full_name : "Previous manager";
                $message = "User designated as Line Manager. {$oldName} has been replaced.";
            }
        }

        $this->logAction($id, $action, "User {$state} for department [{$department->department_name}] by admin.");

        return response()->json([
            'success' => true,
            'is_lm'   => (int)$department->line_manager_id === (int)$id ? 1 : 0,
            'message' => $message,
        ]);
    }

    public function toggleGm($id)
    {
        $systemUser = \App\Models\User::where('user_id', $id)->first();

        if (!$systemUser) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $newState = $systemUser->is_gm ? 0 : 1;

        // If designating as GM, clear any existing GM first (only one GM at a time)
        if ($newState === 1) {
            \App\Models\User::where('is_gm', 1)->update(['is_gm' => 0]);
        }

        $systemUser->is_gm = $newState;
        $systemUser->save();

        $state  = $newState ? 'designated as GM' : 'removed from GM role';
        $action = $newState ? 'GM Designated' : 'GM Removed';
        $this->logAction($id, $action, "User {$state} by admin.");

        return response()->json([
            'success' => true,
            'is_gm'   => $newState,
            'message' => $newState
                ? 'User designated as General Manager. Previous GM (if any) has been unset.'
                : 'GM designation removed from this user.',
        ]);
    }



    public function toggleLiaison($id)
    {
        $systemUser = \App\Models\User::where('user_id', $id)->first();

        if (!$systemUser) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $newState = $systemUser->is_liaison ? 0 : 1;

        $systemUser->is_liaison = $newState;
        $systemUser->save();

        $state  = $newState ? 'designated as Liaison Officer' : 'removed from Liaison Officer role';
        $action = $newState ? 'Liaison Officer Designated' : 'Liaison Officer Removed';
        $this->logAction($id, $action, "User {$state} by admin.");

        return response()->json([
            'success'    => true,
            'is_liaison' => $newState,
            'message'    => $newState
                ? 'User designated as Liaison Officer.'
                : 'Liaison Officer designation removed from this user.',
        ]);
    }



    private function logAction($refId, $action, $remark)
    {
        SystemLog::create([
            'related_table' => 'employees_list',
            'related_id' => $refId,
            'log_date' => now(),
            'log_action' => $action,
            'log_remark' => $remark,
            'logger_type' => 'admin',
            'logged_by' => Auth::user()->user_id ?? 1,
        ]);
    }

    public function getData(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 15);

        // Filter by user/email search if needed
        $query = Employee::with(['department', 'designation', 'systemUser'])
            ->where('is_deleted', 0)
            ->where('is_hidden', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('employee_no', 'LIKE', "%{$search}%")
                  ->orWhere('employee_email', 'LIKE', "%{$search}%")
                  ->orWhereHas('systemUser', function($sq) use ($search) {
                      $sq->where('user_type', 'LIKE', "%{$search}%")
                        ->orWhere('user_email', 'LIKE', "%{$search}%");
                  });
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

        $query->orderBy('employee_id', 'desc');

        $users = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ]
        ]);
    }
}
