<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskStatus;
use App\Models\TaskPriority;
use App\Models\Employee;
use App\Models\Department;
use App\Models\SystemLog;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $viewMode = $request->input('view_mode', 'my_tasks');
        $statusId = $request->input('status_id');
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        // Tasks pending line manager approval (submitted by this user)
        if ($viewMode === 'submitted') {
            $tasks = Task::with(['status', 'priority', 'assignedBy', 'assignedTo'])
                ->where('assigned_by', $employeeId)
                ->whereNotNull('pending_line_manager_id')
                ->where('pending_line_manager_id', '!=', 0)
                ->orderBy('task_id', 'desc')->paginate(15);

        } elseif ($viewMode === 'rejected') {
            $tasks = Task::with(['status', 'priority', 'assignedBy', 'assignedTo'])
                ->where('assigned_by', $employeeId)
                ->where('is_rejected', 1)
                ->orderBy('task_id', 'desc')->paginate(15);

        } elseif ($viewMode === 'rejected_by_me') {
            $tasks = Task::with(['status', 'priority', 'assignedBy', 'assignedTo'])
                ->where('is_rejected', 1)
                ->whereHas('assignedBy', function ($q) use ($employeeId) {
                    $deptIds = Department::where('line_manager_id', $employeeId)->pluck('department_id');
                    $q->whereIn('department_id', $deptIds);
                })
                ->orderBy('task_id', 'desc')->paginate(15);

        } else {
            $query = Task::with(['status', 'priority', 'assignedBy', 'assignedTo', 'subtasks.status', 'subtasks.priority', 'subtasks.assignedBy', 'subtasks.assignedTo'])
                ->where(function ($q) {
                    $q->whereNull('pending_line_manager_id')->orWhere('pending_line_manager_id', 0);
                })
                ->where('is_rejected', 0);

            if ($viewMode == 'others_tasks') {
                $query->where('assigned_by', $employeeId);
            } else {
                $query->where('assigned_to', $employeeId);
            }

            if ($statusId) {
                $query->where('status_id', $statusId);
            }

            $tasks = $query->orderBy('task_id', 'desc')->paginate(15);
        }

        $statuses = TaskStatus::all();
        $priorities = TaskPriority::all();

        $deptId = $user->employee ? $user->employee->department_id : null;
        $employees = Employee::where('is_deleted', 0)
            ->when($deptId, fn($q) => $q->where('department_id', $deptId))
            ->orderBy('first_name')->get();

        $pendingCount = Task::where('pending_line_manager_id', $employeeId)->count();

        $submittedCount = Task::where('assigned_by', $employeeId)
            ->whereNotNull('pending_line_manager_id')
            ->where('pending_line_manager_id', '!=', 0)
            ->count();

        $rejectedCount = Task::where('assigned_by', $employeeId)->where('is_rejected', 1)->count();

        $deptIds = Department::where('line_manager_id', $employeeId)->pluck('department_id');
        $rejectedByMeCount = Task::where('is_rejected', 1)
            ->whereHas('assignedBy', fn($q) => $q->whereIn('department_id', $deptIds))
            ->count();

        $isLineManager = Department::where('line_manager_id', $employeeId)->exists();

        return view('emp.tasks.index', compact('tasks', 'statuses', 'priorities', 'employees', 'viewMode', 'statusId', 'pendingCount', 'submittedCount', 'rejectedCount', 'rejectedByMeCount', 'isLineManager'));
    }

    public function show($id)
    {
        $task = Task::with(['status', 'priority', 'assignedBy', 'assignedTo', 'logs.logger', 'comments.commenter'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $task
        ]);
    }

    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'comment_body' => 'required|string|max:2000',
        ]);

        $employeeId = Auth::user()->employee ? Auth::user()->employee->employee_id : 0;

        $task = Task::findOrFail($id);

        $comment = TaskComment::create([
            'task_id' => $task->task_id,
            'employee_id' => $employeeId,
            'comment_body' => $request->comment_body,
        ]);

        $comment->load('commenter');

        return response()->json([
            'success' => true,
            'comment' => $comment,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_title' => 'required|string|max:255',
            'task_assigned_date' => 'required|date',
            'task_due_date' => 'required|date|after_or_equal:task_assigned_date',
            'priority_id' => 'required|exists:sys_list_priorities,priority_id',
            'task_attachment' => 'nullable|file|max:10240',
            'parent_task_id' => 'nullable|exists:tasks_list,task_id'
        ]);

        $user = Auth::user();
        $employee = $user->employee;
        $employeeId = $employee ? $employee->employee_id : 0;

        // Always fetch the freshest line manager at task creation time
        $lineManagerId = null;
        if ($employee && $employee->department_id) {
            // Fresh query — gets current line_manager_id even if it changed recently
            $lineManagerId = Department::where('department_id', $employee->department_id)
                ->value('line_manager_id');
        }
        // Fallback: if this department has no line manager, get the most recently updated dept that has one
        if (!$lineManagerId) {
            $lineManagerId = Department::whereNotNull('line_manager_id')
                ->orderBy('updated_at', 'desc')
                ->value('line_manager_id');
        }

        // If the creator IS the line manager, they don't need to approve their own task
        if ($lineManagerId && $lineManagerId == $employeeId) {
            $lineManagerId = null;
        }

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
        // Store the suggested assignee from the creator; line manager can change or confirm
        $task->assigned_to = $request->filled('assigned_to') ? $request->assigned_to : null;
        $task->pending_line_manager_id = $lineManagerId;
        $task->priority_id = $request->priority_id;
        $task->parent_task_id = $request->parent_task_id ?? 0;

        if ($request->hasFile('task_attachment')) {
            $file = $request->file('task_attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/tasks'), $filename);
            $task->task_attachment = 'uploads/tasks/' . $filename;
        }

        $firstStatus = TaskStatus::orderBy('status_id')->first();
        $task->status_id = $firstStatus ? $firstStatus->status_id : 1;

        $task->save();

        // Notify line manager
        if ($lineManagerId) {
            \App\Services\NotificationService::send(
                "A new task requires your review & assignment: " . $task->task_title,
                "emp/tasks/pending",
                $lineManagerId
            );
        }

        // Initial log
        SystemLog::create([
            'log_action' => 'Task_Added',
            'log_remark' => 'Task created — pending line manager assignment',
            'related_table' => 'tasks_list',
            'related_id' => $task->task_id,
            'log_date' => now(),
            'logged_by' => $employeeId,
            'logger_type' => 'employees_list',
            'log_type' => 'int'
        ]);

        return response()->json(['success' => true, 'message' => 'Task submitted to your line manager for assignment!']);
    }

    /**
     * List tasks pending this employee as line manager.
     */
    public function pendingTasks(Request $request)
    {
        $employeeId = Auth::user()->employee ? Auth::user()->employee->employee_id : 0;

        $tasks = Task::with(['status', 'priority', 'assignedBy', 'assignedTo'])
            ->where('pending_line_manager_id', $employeeId)
            ->orderBy('task_id', 'desc')
            ->get();

        $employees = Employee::where('is_deleted', 0)->orderBy('first_name')->get();

        return view('emp.tasks.pending_assignments', compact('tasks', 'employees'));
    }

    /**
     * Line manager assigns a pending task to an employee.
     */
    public function assignTask(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:employees_list,employee_id',
        ]);

        $employeeId = Auth::user()->employee ? Auth::user()->employee->employee_id : 0;

        $task = Task::where('task_id', $id)
            ->where('pending_line_manager_id', $employeeId)
            ->firstOrFail();

        $task->assigned_to = $request->assigned_to;
        $task->pending_line_manager_id = null;
        $task->task_assigned_date = now();
        $task->save();

        // Notify assigned employee
        \App\Services\NotificationService::send(
            "You have been assigned a new task: " . $task->task_title,
            "emp/tasks",
            $task->assigned_to
        );

        // Notify task creator
        \App\Services\NotificationService::send(
            "Your task has been assigned by your line manager: " . $task->task_title,
            "emp/tasks",
            $task->assigned_by
        );

        // Log
        SystemLog::create([
            'log_action' => 'Task Assigned',
            'log_remark' => 'Task assigned by line manager to employee #' . $request->assigned_to,
            'related_table' => 'tasks_list',
            'related_id' => $task->task_id,
            'log_date' => now(),
            'logged_by' => $employeeId,
            'logger_type' => 'employees_list',
            'log_type' => 'int'
        ]);

        return response()->json(['success' => true, 'message' => 'Task assigned successfully!']);
    }

    /**
     * Line manager rejects a pending task.
     */
    public function rejectTask(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $employeeId = Auth::user()->employee ? Auth::user()->employee->employee_id : 0;

        $task = Task::where('task_id', $id)
            ->where('pending_line_manager_id', $employeeId)
            ->firstOrFail();

        $creatorId = $task->assigned_by;
        $taskTitle = $task->task_title;
        $reason = $request->rejection_reason;

        // Mark as rejected (keep in DB so creator can review and resubmit)
        $task->is_rejected = 1;
        $task->rejection_reason = $reason;
        $task->pending_line_manager_id = null;
        $task->save();

        // Notify creator
        \App\Services\NotificationService::send(
            "Your task \"" . $taskTitle . "\" was rejected. Reason: " . $reason . ". Please review and resubmit.",
            "emp/tasks",
            $creatorId
        );

        return response()->json(['success' => true, 'message' => 'Task rejected. Creator has been notified to review and resubmit.']);
    }

    /**
     * Creator resubmits a rejected task for line manager approval.
     */
    public function resubmitTask(Request $request, $id)
    {
        $request->validate([
            'task_title' => 'required|string|max:255',
            'task_description' => 'nullable|string',
        ]);

        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $task = Task::where('task_id', $id)
            ->where('assigned_by', $employeeId)
            ->where('is_rejected', 1)
            ->firstOrFail();

        // Get fresh line manager
        $lineManagerId = null;
        if ($user->employee && $user->employee->department_id) {
            $lineManagerId = Department::where('department_id', $user->employee->department_id)->value('line_manager_id');
        }
        if (!$lineManagerId) {
            $lineManagerId = Department::whereNotNull('line_manager_id')->orderBy('updated_at', 'desc')->value('line_manager_id');
        }
        if ($lineManagerId == $employeeId)
            $lineManagerId = null;

        $task->task_title = $request->task_title;
        $task->task_description = $request->task_description;
        $task->is_rejected = 0;
        $task->rejection_reason = null;
        $task->pending_line_manager_id = $lineManagerId;
        $task->save();

        if ($lineManagerId) {
            \App\Services\NotificationService::send(
                "A resubmitted task requires your review: " . $task->task_title,
                "emp/tasks/pending",
                $lineManagerId
            );
        }

        return response()->json(['success' => true, 'message' => 'Task resubmitted for approval!']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'log_remark' => 'nullable|string',
        ]);

        $task = Task::findOrFail($id);
        if ($task->assigned_to != Auth::user()->employee->employee_id && $task->assigned_by != Auth::user()->employee->employee_id) {
            abort(403);
        }

        $logAction = "Update";

        if ($request->has('status_id')) {
            $task->status_id = $request->status_id;
            $logAction = "Status Update";
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
            SystemLog::create([
                'log_action' => $logAction,
                'log_remark' => $request->log_remark,
                'related_table' => 'tasks_list',
                'related_id' => $id,
                'log_date' => now(),
                'logged_by' => Auth::user()->employee->employee_id,
                'logger_type' => 'employees_list',
                'log_type' => 'int'
            ]);
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

        $query = Task::with(['status', 'priority', 'assignedBy', 'assignedTo', 'subtasks.status', 'subtasks.priority', 'subtasks.assignedBy', 'subtasks.assignedTo'])
            ->where(function ($q) {
                $q->whereNull('pending_line_manager_id')->orWhere('pending_line_manager_id', 0);
            });

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
