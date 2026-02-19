<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeePass;
use App\Models\Department;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Hash;
use App\Models\Asset;
use App\Models\AssetCategory;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Filter by user/email search if needed
        $query = Employee::with(['department', 'systemUser'])
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
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
            'employee_email' => 'required|unique:employees_list,employee_email',
            'password' => 'required|string|min:6',
        ]);

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
        $emp->save();

        // 2. Save Password
        $pass = new EmployeePass();
        $pass->employee_id = $emp->employee_id;
        $pass->pass_value = Hash::make($request->password);
        $pass->is_active = 1;
        $pass->save();

        // 3. Get User Type from Department
        $dept = Department::find($request->department_id);
        $userType = $dept ? $dept->user_type : 'NA';

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
        $sysUser->save();

        // Log
        $this->logAction($emp->employee_id, 'User Created', "User {$emp->first_name} {$emp->last_name} created.");

        return redirect()->back()->with('success', 'User created successfully.');
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

        return view('admin.users.show', compact('user', 'assets', 'logs', 'availableAssets'));
    }

    public function updateStatus(Request $request, $id)
    {
        $systemUser = \App\Models\User::where('user_id', $id)->first();

        if ($systemUser) {
            $newStatus = $request->status; // 1 = Activate, 0 = Deactivate

            $logRemark = "Status changed to " . ($newStatus ? 'Active' : 'Inactive');
            $action = $newStatus ? 'User Activated' : 'User Deactivated';

            // Specific logic for Deactivation
            if ($newStatus == 0) {
                $request->validate([
                    'log_remark' => 'required|string',
                    'log_attachment' => 'nullable|file|max:10240'
                ]);
                $logRemark = $request->log_remark;

                // Handle Attachment
                if ($request->hasFile('log_attachment')) {
                    $file = $request->file('log_attachment');
                    $filename = 'deactivate_' . time() . '_' . $id . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/admin_logs'), $filename);

                    // Append attachment link to remark
                    $logRemark .= "\n[Attachment: uploads/admin_logs/$filename]";
                }
            }

            $systemUser->is_active = $newStatus;
            $systemUser->save();

            $this->logAction($id, $action, $logRemark);

            return redirect()->back()->with('success', "User status updated successfully.");
        }

        return redirect()->back()->with('error', "System user record not found.");
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6',
            'log_remark' => 'required|string'
        ]);

        $user = Employee::findOrFail($id);
        $pass = EmployeePass::where('employee_id', $id)->first() ?? new EmployeePass();
        $pass->employee_id = $id;
        $pass->pass_value = Hash::make($request->password);
        $pass->is_active = 1;
        $pass->save();

        $this->logAction($id, 'Password Reset', $request->log_remark);

        return redirect()->back()->with('success', "Password reset successfully.");
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

    private function logAction($refId, $action, $remark)
    {
        SystemLog::create([
            'related_table' => 'employees_list',
            'related_id' => $refId,
            'log_date' => now(),
            'log_action' => $action,
            'log_remark' => $remark,
            'logger_type' => 'admin',
            'logged_by' => auth()->id() ?? 1,
        ]);
    }

    public function getData(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 15);

        // Filter by user/email search if needed
        $query = Employee::with(['department', 'systemUser'])
            ->where('is_deleted', 0)
            ->where('is_hidden', 0)
            ->orderBy('employee_id', 'desc');

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
