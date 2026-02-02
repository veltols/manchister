<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskPriority;
use App\Models\TaskStatus;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['priority', 'status', 'assignedTo', 'assignedBy'])
            ->orderBy('task_id', 'desc')
            ->paginate(10);

        return view('hr.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $priorities = TaskPriority::all();
        $employees = Employee::orderBy('first_name')->get(); // Assuming Employee model has first_name
        return view('hr.tasks.create', compact('priorities', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_title' => 'required|string|max:255',
            'task_description' => 'required|string',
            'assigned_to' => 'required|exists:employees_list,employee_id',
            'priority_id' => 'required|exists:sys_lists_priority,priority_id', // Assuming sys_lists_priority table logic or similar from model
            'task_start_date' => 'required|date',
            'task_due_date' => 'required|date|after_or_equal:task_start_date',
        ]);

        $task = new Task();
        $task->task_title = $request->task_title;
        $task->task_description = $request->task_description;
        $task->assigned_to = $request->assigned_to;
        $task->priority_id = $request->priority_id;
        $task->task_assigned_date = $request->task_start_date; // Using start_date as assigned_date or similar
        $task->task_due_date = $request->task_due_date;

        $task->status_id = 1; // Default to 'Pending' or similar ID. Assuming 1 is initial status.
        $task->assigned_by = Auth::id() ?? 0; // Or link to Employee ID if Auth user is linked
        $task->created_at = now(); // If timestamps false, manually set if needed, but model says false.

        $task->save();

        return redirect()->route('hr.tasks.index')->with('success', 'Task created successfully.');
    }

    public function show($id)
    {
        $task = Task::with(['priority', 'status', 'assignedTo', 'assignedBy', 'logs'])->findOrFail($id);
        return view('hr.tasks.show', compact('task'));
    }
}
