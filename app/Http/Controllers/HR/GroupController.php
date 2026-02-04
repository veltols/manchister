<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HrGroup;
use App\Models\SysColor;
use App\Models\HrGroupMember;
use App\Models\HrGroupPost;
use App\Models\HrGroupFile;
use App\Models\HrGroupRole;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $isCom = $request->get('c', 0);
        $groups = HrGroup::with('color')
            ->where('is_commity', $isCom)
            ->where('is_deleted', 0)
            ->get();

        $colors = SysColor::all();
        $employees = Employee::orderBy('first_name')->get();
        $roles = HrGroupRole::all();

        return view('hr.groups.index', compact('groups', 'colors', 'isCom', 'employees', 'roles'));
    }

    public function show($id)
    {
        $group = HrGroup::with(['color', 'members.employee', 'members.role', 'posts.author', 'files.adder'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $group
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:50',
            'group_color_id' => 'required',
            'is_com' => 'required|integer',
        ]);

        $group = HrGroup::create([
            'group_name' => $request->group_name,
            'group_desc' => $request->group_desc,
            'group_color_id' => $request->group_color_id,
            'is_commity' => $request->is_com,
            'added_by' => 1, // Defaulting to 1 for now, should be Auth::id() if integrated
            'added_date' => now(),
            'is_deleted' => 0,
            'is_archieve' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Group created successfully',
            'data' => $group
        ]);
    }

    public function addMember(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:z_groups_list,group_id',
            'employee_id' => 'required|exists:employees_list,employee_id',
            'group_role_id' => 'required|exists:z_groups_list_roles,group_role_id',
        ]);

        $member = HrGroupMember::create([
            'group_id' => $request->group_id,
            'employee_id' => $request->employee_id,
            'group_role_id' => $request->group_role_id,
            'added_by' => 1,
            'added_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Member added successfully'
        ]);
    }

    public function addPost(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:z_groups_list,group_id',
            'post_text' => 'required|string',
        ]);

        $post = HrGroupPost::create([
            'group_id' => $request->group_id,
            'post_text' => $request->post_text,
            'post_type' => 'text',
            'added_by' => 1,
            'added_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post added successfully'
        ]);
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:z_groups_list,group_id',
            'file_name' => 'required|string',
            'uploaded_file' => 'required|file',
        ]);

        if ($request->hasFile('uploaded_file')) {
            $file = $request->file('uploaded_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/groups'), $fileName);

            HrGroupFile::create([
                'group_id' => $request->group_id,
                'file_name' => $request->file_name,
                'file_path' => $fileName,
                'added_by' => 1,
                'added_date' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully'
        ]);
    }
}
