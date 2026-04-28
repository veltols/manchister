<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupPost;
use App\Models\GroupFile;
use App\Models\SystemLog;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        // Fetch groups where user is a member or created by user
        $groupIds = GroupMember::where('employee_id', $employeeId)->pluck('group_id');

        $isArchived = $request->query('archived', 0);

        $groups = Group::with('color')
            ->where(function($q) use ($groupIds, $employeeId) {
                $q->whereIn('group_id', $groupIds)
                  ->orWhere('added_by', $employeeId);
            })
            ->where('is_archieve', $isArchived)
            ->where('is_deleted', 0)
            ->distinct()
            ->orderBy('group_id', 'desc')
            ->get();

        $colors = \App\Models\SysColor::all();
        $employees = \App\Models\Employee::where('is_deleted', 0)
            ->whereHas('systemUser', function($q) { $q->where('is_active', 1); })
            ->orderBy('first_name')
            ->get();
        $roles = \App\Models\GroupRole::all();

        return view('emp.groups.index', compact('groups', 'colors', 'employees', 'roles'));
    }

    public function show($id)
    {
        $group = Group::with(['color', 'members.employee', 'members.role', 'posts.author', 'files.adder'])
            ->findOrFail($id);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        // Check if member
        $membership = GroupMember::where('group_id', $id)->where('employee_id', $employeeId)->first();
        $isMember = $membership ? true : false;
        
        if (!$isMember && $group->added_by != $employeeId) {
            abort(403);
        }

        if (request()->wantsJson() || request()->ajax()) {
            $group->current_user_accepted = $membership ? $membership->is_accepted : ($group->added_by == $employeeId ? 1 : 0);
            return response()->json([
                'success' => true,
                'data' => $group
            ]);
        }

        return view('emp.groups.show', compact('group'));
    }

    public function post(Request $request, $id)
    {
        $request->validate([
            'post_text' => 'required_without:attachment|nullable|string',
            'attachment' => 'nullable|file|max:10240'
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $post = new GroupPost();
        $post->post_text = $request->post_text ?? '';
        $post->post_type = 'text';
        $post->group_id = $id;
        $post->added_by = $employeeId;
        $post->added_date = now();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $targetDir = public_path('uploads/groups');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $fileName);

            $post->post_text = $fileName;
            $post->post_type = 'document';
            $post->post_file_path = 'uploads/groups/' . $fileName;
            $post->post_file_name = $file->getClientOriginalName();

            $extension = strtolower($file->getClientOriginalExtension());
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $post->post_type = 'image';
            }
        }

        $post->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Message posted'
            ]);
        }

        return redirect()->back()->with('success', 'Message posted');
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'file_name' => 'required',
            'file_path' => 'required|file|max:10240',
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/groups'), $filename);

            $gFile = new GroupFile();
            $gFile->file_name = $request->file_name;
            $gFile->file_path = 'uploads/groups/' . $filename;
            $gFile->file_version = '1.0';
            $gFile->group_id = $id;
            $gFile->added_by = $employeeId;
            $gFile->added_date = now();
            $gFile->save();

            // Also post it to the feed
            $post = new GroupPost();
            $post->post_text = 'Uploaded a new file: ' . $request->file_name;
            $post->post_type = 'document';
            $post->post_file_path = $gFile->file_path;
            $post->post_file_name = $gFile->file_name;
            $post->group_id = $id;
            $post->added_by = $employeeId;
            $post->added_date = now();
            $post->save();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'File uploaded'
            ]);
        }

        return redirect()->back()->with('success', 'File uploaded');
    }
    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:50',
            'group_color_id' => 'required',
            'is_com' => 'required|integer',
        ]);

        $employeeId = Auth::user()->employee->employee_id ?? 0;

        $group = Group::create([
            'group_name' => $request->group_name,
            'group_desc' => $request->group_desc,
            'group_color_id' => $request->group_color_id,
            'is_commity' => $request->is_com,
            'added_by' => $employeeId,
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

        $employeeId = Auth::user()->employee->employee_id ?? 0;
        $group = Group::find($request->group_id);

        $member = GroupMember::create([
            'group_id' => $request->group_id,
            'employee_id' => $request->employee_id,
            'group_role_id' => $request->group_role_id,
            'added_by' => $employeeId,
            'added_date' => now(),
            'is_accepted' => 0, // Pending
        ]);

        // Send Notification
        \App\Models\Notification::create([
            'notification_date' => now(),
            'notification_text' => 'You have been invited to join the group: ' . $group->group_name . '. Please accept the invitation to participate.',
            'related_page' => route('emp.groups.index'),
            'employee_id' => $request->employee_id,
            'is_seen' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invitation sent successfully'
        ]);
    }

    public function acceptInvitation(Request $request, $id)
    {
        $employeeId = Auth::user()->employee->employee_id ?? 0;
        $member = GroupMember::where('group_id', $id)
            ->where('employee_id', $employeeId)
            ->firstOrFail();

        $member->is_accepted = 1;
        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'Invitation accepted'
        ]);
    }

    public function removeMember(Request $request, $id, $memberId)
    {
        $employeeId = Auth::user()->employee->employee_id ?? 0;
        $group = Group::findOrFail($id);
        
        if ($group->added_by != $employeeId) {
            $currentUserMember = GroupMember::where('group_id', $id)->where('employee_id', $employeeId)->first();
            if (!$currentUserMember || $currentUserMember->group_role_id != 1) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        }
        
        GroupMember::where('group_id', $id)->where('employee_id', $memberId)->delete();
        return response()->json(['success' => true, 'message' => 'Member removed']);
    }

    public function archiveGroup(Request $request, $id)
    {
        $employeeId = Auth::user()->employee->employee_id ?? 0;
        $isMember = GroupMember::where('group_id', $id)->where('employee_id', $employeeId)->exists();
        $group = Group::findOrFail($id);
        
        if (!$isMember && $group->added_by != $employeeId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $group->is_archieve = 1;
        $group->save();
        return response()->json(['success' => true, 'message' => 'Group archived']);
    }

    public function restoreGroup(Request $request, $id)
    {
        $employeeId = Auth::user()->employee->employee_id ?? 0;
        $isMember = GroupMember::where('group_id', $id)->where('employee_id', $employeeId)->exists();
        $group = Group::findOrFail($id);
        
        if (!$isMember && $group->added_by != $employeeId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $group->is_archieve = 0;
        $group->save();
        return response()->json(['success' => true, 'message' => 'Group restored']);
    }

    public function copyGroup(Request $request, $id)
    {
        $employeeId = Auth::user()->employee->employee_id ?? 0;
        $group = Group::findOrFail($id);
        
        if ($group->added_by != $employeeId) {
            $currentUserMember = GroupMember::where('group_id', $id)->where('employee_id', $employeeId)->first();
            if (!$currentUserMember || $currentUserMember->group_role_id != 1) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        }
        
        $request->validate(['new_name' => 'required|string|max:50']);
        
        $newGroup = $group->replicate();
        $newGroup->group_name = $request->new_name;
        $newGroup->added_date = now();
        $newGroup->save();
        
        $members = GroupMember::where('group_id', $id)->get();
        foreach ($members as $member) {
            $newMember = $member->replicate();
            $newMember->group_id = $newGroup->group_id;
            $newMember->added_date = now();
            $newMember->save();
        }
        
        return response()->json(['success' => true, 'message' => 'Group copied']);
    }
}
