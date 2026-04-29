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
use App\Models\GroupAgenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $isCom = request()->get('c') ?? 0;
        $isArchived = $request->query('archived', 0);

        $groups = HrGroup::with('color')
            ->where('is_commity', $isCom)
            ->where('is_deleted', 0)
            ->where('is_archieve', $isArchived)
            ->get();

        $colors = SysColor::all();
        $employees = Employee::orderBy('first_name')->get();
        $roles = HrGroupRole::all();

        return view('hr.groups.index', compact('groups', 'colors', 'isCom', 'employees', 'roles'));
    }

    public function show($id)
    {
        $group = HrGroup::with(['color', 'members.employee', 'members.role', 'posts.author', 'files.adder', 'agendas'])
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
            'added_by' => 1, // Defaulting to 1 for now, should be auth()->user()->user_id; if integrated
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

        $group = HrGroup::find($request->group_id);
        $hrEmployeeId = Auth::user()->employee->employee_id ?? 0;

        $member = HrGroupMember::create([
            'group_id' => $request->group_id,
            'employee_id' => $request->employee_id,
            'group_role_id' => $request->group_role_id,
            'added_by' => $hrEmployeeId,
            'added_date' => now(),
            'is_accepted' => 0, // Pending
        ]);

        // Send Notification
        \App\Models\Notification::create([
            'notification_date' => now(),
            'notification_text' => 'HR has invited you to join the group: ' . $group->group_name . '. Please accept the invitation to participate.',
            'related_page' => route('emp.groups.index'),
            'employee_id' => $request->employee_id,
            'is_seen' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invitation sent successfully'
        ]);
    }

    public function addPost(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:z_groups_list,group_id',
            'post_text' => 'required_without:attachment|nullable|string',
            'attachment' => 'nullable|file|max:10240'
        ]);

        $post = new \App\Models\HrGroupPost();
        $post->group_id = $request->group_id;
        $post->post_text = $request->post_text ?? '';
        $post->post_type = 'text';
        $post->added_by = 1;
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

    public function removeMember(Request $request, $id, $memberId)
    {
        $group = HrGroup::findOrFail($id);
        HrGroupMember::where('group_id', $id)->where('employee_id', $memberId)->delete();
        return response()->json(['success' => true, 'message' => 'Member removed']);
    }

    public function archiveGroup(Request $request, $id)
    {
        $group = HrGroup::findOrFail($id);
        $group->is_archieve = 1;
        $group->save();
        return response()->json(['success' => true, 'message' => 'Group archived']);
    }

    public function restoreGroup(Request $request, $id)
    {
        $group = HrGroup::findOrFail($id);
        $group->is_archieve = 0;
        $group->save();
        return response()->json(['success' => true, 'message' => 'Group restored']);
    }

    public function copyGroup(Request $request, $id)
    {
        $group = HrGroup::findOrFail($id);
        $request->validate(['new_name' => 'required|string|max:50']);
        
        $newGroup = $group->replicate();
        $newGroup->group_name = $request->new_name;
        $newGroup->added_date = now();
        $newGroup->save();
        
        $members = HrGroupMember::where('group_id', $id)->get();
        foreach ($members as $member) {
            $newMember = $member->replicate();
            $newMember->group_id = $newGroup->group_id;
            $newMember->added_date = now();
            $newMember->save();
        }
        
        return response()->json(['success' => true, 'message' => 'Group copied']);
    }

    // --- Agenda Methods ---
    
    public function storeAgenda(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|string',
            'status' => 'required|string',
        ]);

        $agenda = new GroupAgenda();
        $agenda->group_id = $id;
        $agenda->added_by = Auth::id() ?? 1;
        $agenda->title = $request->title;
        $agenda->description = $request->description;
        $agenda->priority = $request->priority;
        $agenda->status = $request->status;
        $agenda->start_date = $request->start_date;
        $agenda->time_duration = $request->time_duration;
        $agenda->end_date = $request->end_date;
        $agenda->decision_outcome = $request->decision_outcome;
        $agenda->action_items = $request->action_items;
        $agenda->save();

        return response()->json(['success' => true, 'message' => 'Agenda added successfully.']);
    }

    public function updateAgenda(Request $request, $id, $agenda_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|string',
            'status' => 'required|string',
        ]);

        $agenda = GroupAgenda::where('group_id', $id)->findOrFail($agenda_id);
        $agenda->title = $request->title;
        $agenda->description = $request->description;
        $agenda->priority = $request->priority;
        $agenda->status = $request->status;
        $agenda->start_date = $request->start_date;
        $agenda->time_duration = $request->time_duration;
        $agenda->end_date = $request->end_date;
        $agenda->decision_outcome = $request->decision_outcome;
        $agenda->action_items = $request->action_items;
        $agenda->save();

        return response()->json(['success' => true, 'message' => 'Agenda updated successfully.']);
    }

    public function destroyAgenda($id, $agenda_id)
    {
        $agenda = GroupAgenda::where('group_id', $id)->findOrFail($agenda_id);
        $agenda->delete();
        
        return response()->json(['success' => true, 'message' => 'Agenda deleted successfully.']);
    }
}
