<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\TaskPriority;
use App\Models\Employee;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['status', 'priority', 'assignedBy'])
            ->orderBy('task_id', 'desc')
            ->get();

        $statuses = TaskStatus::all();
        $priorities = TaskPriority::all();
        $employees = Employee::orderBy('first_name')->get();

        return view('hr.tasks.index', compact('tasks', 'statuses', 'priorities', 'employees'));
    }

    public function show($id)
    {
        $task = Task::with(['status', 'priority', 'assignedBy', 'logs.logger'])
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
            'task_description' => 'nullable|string',
            'assigned_to' => 'required|exists:employees_list,employee_id',
            'priority_id' => 'required|exists:sys_list_priorities,priority_id',
            'task_due_date' => 'required|date',
        ]);

        $task = new Task();
        $task->task_title = $request->task_title;
        $task->task_description = $request->task_description;
        $task->assigned_to = $request->assigned_to;
        $task->assigned_by = 1; // Default to 1 (admin) or Auth::id()
        $task->task_assigned_date = now();
        $task->task_due_date = $request->task_due_date;
        $task->priority_id = $request->priority_id;
        $task->status_id = 1; // Default 'Pending'
        $task->save();

        // Send Notification to assigned employee
        \App\Services\NotificationService::send(
            "You have been assigned a new task: " . $task->task_title,
            "tasks/list", // Laravel-friendly route
            $task->assigned_to
        );

        // Log creation
        SystemLog::create([
            'log_action' => 'Task Created',
            'log_remark' => 'Task created in system',
            'related_table' => 'tasks_list',
            'related_id' => $task->task_id,
            'log_date' => now(),
            'logged_by' => Auth::id(),
            'logger_type' => 'employees_list',
            'log_type' => 'int'
        ]);

        return redirect()->route('hr.tasks.index')->with('success', 'Task created successfully!');
    }

    public function updateStatus(Request $request)
    {
        Log::info('Task status update request received', ['request' => $request->all()]);

        try {
            $request->validate([
                'task_id' => 'required|exists:tasks_list,task_id',
                'status_id' => 'required|exists:sys_list_status,status_id',
                'log_remark' => 'required|string',
            ]);

            $task = Task::findOrFail($request->task_id);
            $oldStatus = $task->status ? $task->status->status_name : 'Unknown';

            $task->status_id = $request->status_id;
            if ($request->status_id == 4) { // Assuming 4 is Completed based on legacy logic
                $task->task_end_date = now();
            }
            $task->save();

            Log::info('Task status updated in database', [
                'task_id' => $task->task_id,
                'new_status_id' => $task->status_id,
                'old_status' => $oldStatus
            ]);

            $newStatus = TaskStatus::find($request->status_id)->status_name;

            // Send Notification to its assigned_by (manager) or assignee (user) depending on context
            if ($request->status_id == 4) { // Completed
                \App\Services\NotificationService::send(
                    "Task Completed: " . $task->task_title,
                    "hr/tasks", 
                    $task->assigned_by
                );
            }

            // Log update
            SystemLog::create([
                'log_action' => 'Status Update',
                'log_remark' => "Status changed from $oldStatus to $newStatus. Remark: " . $request->log_remark,
                'related_table' => 'tasks_list',
                'related_id' => $task->task_id,
                'log_date' => now(),
                'logged_by' => Auth::id(),
                'logger_type' => 'employees_list',
                'log_type' => 'int'
            ]);

            Log::info('System log created for task update', ['task_id' => $task->task_id]);

            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating task status', [
                'task_id' => $request->task_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update task status: ' . $e->getMessage()
            ], 500);
        }
    }
}
