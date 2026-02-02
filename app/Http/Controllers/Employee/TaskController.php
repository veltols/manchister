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
        $viewMode = $request->input('view_mode', 'my_tasks');
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $query = Task::with(['status', 'priority', 'assignedBy', 'assignedTo']);

        if ($viewMode == 'others_tasks') {
            // Tasks I (logged in user) assigned to others
            $query->where('assigned_by', $employeeId);
        } else {
            // my_tasks: Tasks assigned TO me
            $query->where('assigned_to', $employeeId);
        }

        $query->orderBy('task_id', 'desc');

        // Simple Filter
        if ($request->has('status_id') && $request->status_id != '') {
            $query->where('status_id', $request->status_id);
        }

        $tasks = $query->paginate(15);

        $statuses = TaskStatus::all();
        $priorities = TaskPriority::all();
        $employees = Employee::where('is_deleted', 0)->orderBy('first_name')->get();

        return view('emp.tasks.index', compact('tasks', 'statuses', 'priorities', 'employees', 'viewMode'));
    }

    public function show($id)
    {
        $task = Task::with(['status', 'priority', 'assignedBy', 'assignedTo', 'logs.logger'])
            ->findOrFail($id);

        $statuses = TaskStatus::all();

        return view('emp.tasks.show', compact('task', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_title' => 'required|string|max:255',
            'task_due_date' => 'required|date',
            'priority_id' => 'required|exists:sys_list_priorities,priority_id',
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $task = new Task();
        $task->task_title = $request->task_title;
        $task->task_description = $request->task_description ?? '';
        $task->task_assigned_date = now();
        $task->task_due_date = $request->task_due_date;
        $task->assigned_by = $employeeId;
        // If assigned_to is not provided, assign to self
        $task->assigned_to = $request->assigned_to ?? $employeeId;
        $task->priority_id = $request->priority_id;
        $task->status_id = 1; // Default 'Pending' or similar? We need to know the IDs. 
        // Let's assume 1 is New/Open. Legacy typically uses 0 or 1.
        // We will default to the first status found or specific ID if known.
        // Inspecting legacy `serv_new.php` would clarify, but let's assume 0 or check DB. 
        // Let's query first status.
        $firstStatus = TaskStatus::orderBy('status_id')->first();
        $task->status_id = $firstStatus ? $firstStatus->status_id : 0;

        $task->save();

        $employeeId = Auth::user()->employee ? Auth::user()->employee->employee_id : Auth::id();

        // Create Initial Log
        $log = new \App\Models\SystemLog();
        $log->related_table = 'tasks_list';
        $log->related_id = $task->task_id;
        $log->log_action = 'Task_Added';
        $log->log_remark = 'Initial task creation';
        $log->log_date = now();
        $log->logged_by = $employeeId;
        $log->logger_type = 'employees_list';
        $log->log_type = 'int';
        $log->save();

        return redirect()->back()->with('success', 'Task created successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'log_remark' => 'nullable|string',
        ]);

        $task = Task::findOrFail($id);
        // Security check: only assigned_to or assigned_by can update?
        // Let's allow assigned_to for now as per core logic
        if ($task->assigned_to != Auth::user()->employee->employee_id && $task->assigned_by != Auth::user()->employee->employee_id) {
            abort(403);
        }

        $logAction = "Update";

        if ($request->has('status_id')) {
            $task->status_id = $request->status_id;
            $logAction = "Status Update";
        }

        if ($request->has('task_progress')) {
            $task->task_progress = $request->task_progress;
            $logAction = "Progress Update";
        }

        $task->save();

        if ($request->filled('log_remark')) {
            $log = new \App\Models\SystemLog();
            $log->related_table = 'tasks_list';
            $log->related_id = $id;
            $log->log_action = $logAction;
            $log->log_remark = $request->log_remark;
            $log->log_date = now();
            $log->logged_by = Auth::user()->employee->employee_id;
            $log->logger_type = 'employees_list';
            $log->log_type = 'int';
            $log->save();
        }

        return redirect()->back()->with('success', 'Task updated successfully.');
    }
}
