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
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        // Fetch groups where user is a member or created by user
        $groupIds = GroupMember::where('employee_id', $employeeId)->pluck('group_id');

        $groups = Group::whereIn('group_id', $groupIds)
            ->orWhere('added_by', $employeeId)
            ->distinct()
            ->orderBy('group_id', 'desc')
            ->get();

        return view('emp.groups.index', compact('groups'));
    }

    public function show($id)
    {
        $group = Group::with(['members.employee', 'members.role', 'posts.sender', 'files.uploader'])
            ->findOrFail($id);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        // Check if member
        $isMember = GroupMember::where('group_id', $id)->where('employee_id', $employeeId)->exists();
        if (!$isMember && $group->added_by != $employeeId) {
            abort(403);
        }

        return view('emp.groups.show', compact('group'));
    }

    public function post(Request $request, $id)
    {
        $request->validate(['post_text' => 'required']);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $post = new GroupPost();
        $post->post_text = $request->post_text;
        $post->post_type = 'text';
        $post->group_id = $id;
        $post->added_by = $employeeId;
        $post->added_date = now();
        $post->save();

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

        return redirect()->back()->with('success', 'File uploaded');
    }
}
