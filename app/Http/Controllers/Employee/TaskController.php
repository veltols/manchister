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
        $statusId = $request->input('status_id');
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $query = Task::with(['status', 'priority', 'assignedBy', 'assignedTo']);

        if ($viewMode == 'others_tasks') {
            $query->where('assigned_by', $employeeId);
        } else {
            $query->where('assigned_to', $employeeId);
        }

        if ($statusId) {
            $query->where('status_id', $statusId);
        }

        $query->orderBy('task_id', 'desc');

        $tasks = $query->paginate(15);

        $statuses = TaskStatus::all();
        $priorities = TaskPriority::all();
        $employees = Employee::where('is_deleted', 0)->orderBy('first_name')->get();

        return view('emp.tasks.index', compact('tasks', 'statuses', 'priorities', 'employees', 'viewMode', 'statusId'));
    }

    public function show($id)
    {
        $task = Task::with(['status', 'priority', 'assignedBy', 'assignedTo', 'logs.logger'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $task
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_title' => 'required|string|max:255',
            'task_assigned_date' => 'required|date',
            'task_due_date' => 'required|date|after_or_equal:task_assigned_date',
            'priority_id' => 'required|exists:sys_list_priorities,priority_id',
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $task = new Task();
        $task->task_title = $request->task_title;
        $task->task_description = $request->task_description ?? '';

        $assignedDate = $request->task_assigned_date;
        if ($request->filled('start_time')) {
            $assignedDate .= ' ' . $request->start_time;
        }
        $task->task_assigned_date = $assignedDate;

        $dueDate = $request->task_due_date;
        if ($request->filled('end_time')) {
            $dueDate .= ' ' . $request->end_time;
        }
        $task->task_due_date = $dueDate;

        $task->assigned_by = $employeeId;
        $task->assigned_to = $request->assigned_to ?? $employeeId;
        $task->priority_id = $request->priority_id;

        // Default Status (New/Open)
        $firstStatus = TaskStatus::orderBy('status_id')->first();
        $task->status_id = $firstStatus ? $firstStatus->status_id : 1;

        $task->save();

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

        return response()->json(['success' => true, 'message' => 'Task created successfully!']);
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
            // Auto-set progress to 100% when status is Done/Completed
            if ($request->status_id == 4) {
                $task->task_progress = 100;
            }
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

        return response()->json(['success' => true, 'message' => 'Task updated successfully.']);
    }

    public function getData(Request $request)
    {
        $viewMode = $request->input('view_mode', 'my_tasks');
        $statusId = $request->input('status_id');
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;
        $perPage = $request->input('per_page', 15);

        $query = Task::with(['status', 'priority', 'assignedBy', 'assignedTo']);

        if ($viewMode == 'others_tasks') {
            $query->where('assigned_by', $employeeId);
        } else {
            $query->where('assigned_to', $employeeId);
        }

        if ($statusId) {
            $query->where('status_id', $statusId);
        }

        $query->orderBy('task_id', 'desc');

        $tasks = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $tasks->items(),
            'pagination' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
                'from' => $tasks->firstItem(),
                'to' => $tasks->lastItem(),
            ]
        ]);
    }
}
