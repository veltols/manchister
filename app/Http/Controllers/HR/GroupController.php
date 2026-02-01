<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupPost;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->employee_id ?? Auth::id();
        
        // Groups user is member of
        $groups = Group::whereHas('members', function($q) use ($userId){
            $q->where('employee_id', $userId);
        })->orWhere('added_by', $userId)
          ->orderBy('group_id', 'desc')
          ->get();
          
        return view('hr.groups.index', compact('groups'));
    }

    public function show($id)
    {
        $group = Group::with(['members.employee', 'posts.sender', 'files'])->findOrFail($id);
        $employees = Employee::where('is_deleted', 0)->orderBy('first_name')->get();
        return view('hr.groups.show', compact('group', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string',
            'group_desc' => 'nullable|string',
        ]);

        $group = new Group();
        $group->group_name = $request->group_name;
        $group->group_desc = $request->group_desc ?? '';
        $group->added_by = Auth::user()->employee_id ?? Auth::id();
        $group->added_date = now();
        $group->group_color_id = 1; // Default
        $group->is_commity = 0; // Default
        $group->is_archieve = 0;
        $group->is_deleted = 0;
        $group->save();
        
        // Add Creator as Member
        $mem = new GroupMember();
        $mem->group_id = $group->group_id;
        $mem->employee_id = $group->added_by;
        $mem->added_date = now();
        $mem->added_by = $group->added_by;
        $mem->save();

        return redirect()->route('hr.groups.show', $group->group_id);
    }

    public function storePost(Request $request, $groupId)
    {
        $request->validate([
             'post_text' => 'required|string'
        ]);
        
        $post = new GroupPost();
        $post->group_id = $groupId;
        $post->post_text = $request->post_text;
        $post->post_type = 'text';
        $post->added_by = Auth::user()->employee_id ?? Auth::id();
        $post->added_date = now();
        $post->save();
        
        return redirect()->back();
    }
    
    public function addMember(Request $request, $groupId)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees_list,employee_id'
        ]);
        
        // Check if exists
        $exists = GroupMember::where('group_id', $groupId)->where('employee_id', $request->employee_id)->exists();
        if(!$exists){
            $mem = new GroupMember();
            $mem->group_id = $groupId;
            $mem->employee_id = $request->employee_id;
            $mem->added_date = now();
            $mem->added_by = Auth::user()->employee_id ?? Auth::id();
            $mem->save();
        }
        
        return redirect()->back()->with('success', 'Member added.');
    }
}
