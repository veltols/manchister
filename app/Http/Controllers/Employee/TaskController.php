<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\TaskPriority;
use App\Models\Employee;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['status', 'priority', 'assignedBy'])
            ->where('assigned_to', Auth::id())
            ->orderBy('task_id', 'desc');

        // Simple Filter
        if($request->has('status_id') && $request->status_id != ''){
            $query->where('status_id', $request->status_id);
        }

        $tasks = $query->paginate(15);
        
        $statuses = TaskStatus::all();
        $priorities = TaskPriority::all();
        // For assigning tasks, maybe allow employees to self-assign or assign to others? 
        // Legacy implies they can create tasks ("Add_new_task" button in view).
        // If they create, they likely assign to themselves or others. Let's assume self or others.
        // For now, let's just show all employees for assignment if needed, or default to self.
        $employees = Employee::where('is_deleted', 0)->orderBy('first_name')->get();

        return view('emp.tasks.index', compact('tasks', 'statuses', 'priorities', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_title' => 'required|string|max:255',
            'task_due_date' => 'required|date',
            'priority_id' => 'required|exists:sys_list_priorities,priority_id',
        ]);

        $task = new Task();
        $task->task_title = $request->task_title;
        $task->task_description = $request->task_description ?? '';
        $task->task_assigned_date = now();
        $task->task_due_date = $request->task_due_date;
        $task->assigned_by = Auth::id();
        // If assigned_to is not provided, assign to self
        $task->assigned_to = $request->assigned_to ?? Auth::id(); 
        $task->priority_id = $request->priority_id;
        $task->status_id = 1; // Default 'Pending' or similar? We need to know the IDs. 
        // Let's assume 1 is New/Open. Legacy typically uses 0 or 1.
        // We will default to the first status found or specific ID if known.
        // Inspecting legacy `serv_new.php` would clarify, but let's assume 0 or check DB. 
        // Let's query first status.
        $firstStatus = TaskStatus::orderBy('status_id')->first();
        $task->status_id = $firstStatus ? $firstStatus->status_id : 0;
        
        $task->save();

        return redirect()->back()->with('success', 'Task created successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $task = Task::where('task_id', $id)->where('assigned_to', Auth::id())->firstOrFail();
        
        if($request->has('status_id')){
            $task->status_id = $request->status_id;
            $task->save();
             return redirect()->back()->with('success', 'Task status updated.');
        }
         return redirect()->back();
    }
}
