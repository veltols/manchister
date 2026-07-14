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
use App\Models\Permission;

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
            $tasks = Task::with(['status', 'priority', 'assignedBy', 'assignedTo', 'department'])
                ->where('assigned_by', $employeeId)
                ->whereNotNull('pending_line_manager_id')
                ->where('pending_line_manager_id', '!=', 0)
                ->where('status_id', '!=', 5)
                ->orderBy('task_id', 'desc')->paginate(15);

        } elseif ($viewMode === 'pending') {
            // Tasks pending THIS user's LM approval
            $tasks = Task::with(['status', 'priority', 'assignedBy', 'assignedTo', 'department'])
                ->where('pending_line_manager_id', $employeeId)
                ->orderBy('task_id', 'desc')->paginate(15);

        } elseif ($viewMode === 'rejected') {
            $tasks = Task::with(['status', 'priority', 'assignedBy', 'assignedTo', 'department'])
                ->where('assigned_by', $employeeId)
                ->where('is_rejected', 1)
                ->orderBy('task_id', 'desc')->paginate(15);

        } elseif ($viewMode === 'rejected_by_me') {
            $tasks = Task::with(['status', 'priority', 'assignedBy', 'assignedTo', 'department'])
                ->where('is_rejected', 1)
                ->whereHas('assignedBy', function ($q) use ($employeeId) {
                    $deptIds = Department::where('line_manager_id', $employeeId)->pluck('department_id');
                    $q->whereIn('department_id', $deptIds);
                })
                ->orderBy('task_id', 'desc')->paginate(15);

        } elseif ($viewMode === 'gm_overview') {
            // GM-only: all active tasks across all departments
            $query = Task::with([
                'status',
                'priority',
                'assignedBy',
                'assignedTo',
                'department',
                'subtasks' => function ($q) {
                    $q->where(function ($sq) {
                        $sq->whereNull('pending_line_manager_id')->orWhere('pending_line_manager_id', 0);
                    })->where('is_rejected', 0);
                },
                'subtasks.status',
                'subtasks.priority',
                'subtasks.assignedBy',
                'subtasks.assignedTo',
                'subtasks.department'
            ])
                ->where(function ($q) {
                    $q->whereNull('pending_line_manager_id')->orWhere('pending_line_manager_id', 0);
                })
                ->where('is_rejected', 0);

            $filterDept = request()->input('dept_id');
            if ($filterDept) {
                $query->where(function($q) use ($filterDept) {
                    $q->where('department_id', $filterDept)
                      ->orWhereHas('assignedTo', fn($sq) => $sq->where('department_id', $filterDept));
                });
            }
            if ($statusId) {
                $query->where('status_id', $statusId);
            }
            $tasks = $query->where(function($q) {
                    $q->whereNull('parent_task_id')->orWhere('parent_task_id', 0);
                })->orderBy('task_id', 'desc')->paginate(15);

        } else {
            // MAIN ACTIVE LISTS (My Tasks / Others' Tasks)
            // MUST ONLY SHOW APPROVED TASKS
            $query = Task::with([
                'status',
                'priority',
                'assignedBy',
                'assignedTo',
                'department',
                'subtasks' => function ($q) {
                    $q->where(function ($sq) {
                        $sq->whereNull('pending_line_manager_id')->orWhere('pending_line_manager_id', 0);
                    })->where('is_rejected', 0);
                },
                'subtasks.status',
                'subtasks.priority',
                'subtasks.assignedBy',
                'subtasks.assignedTo',
                'subtasks.department'
            ])
                ->where(function ($q) {
                    $q->whereNull('pending_line_manager_id')->orWhere('pending_line_manager_id', 0);
                })
                ->where('is_rejected', 0);

            if ($viewMode == 'others_tasks') {
                $query->where('assigned_by', $employeeId);
            } else {
                // Default: My Tasks (Assigned to Me)
                $query->where('assigned_to', $employeeId);
            }

            if ($statusId) {
                $query->where('status_id', $statusId);
            }

            // Only top-level tasks for main list
            $query->where(function ($q) {
                $q->whereNull('parent_task_id')->orWhere('parent_task_id', 0);
            });

            $tasks = $query->orderBy('task_id', 'desc')->paginate(15);
        }

        $statuses = TaskStatus::all();
        $priorities = TaskPriority::all();

        $deptId = $user->employee ? $user->employee->department_id : null;
        $employees = Employee::where('is_deleted', 0)
            ->when($deptId, fn($q) => $q->where('department_id', $deptId))->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })
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
        $isGm = (bool) ($user->is_gm ?? false);
        $departments = Department::orderBy('department_name')->get();
        return view('emp.tasks.index', compact('tasks', 'statuses', 'priorities', 'employees', 'viewMode', 'statusId', 'pendingCount', 'submittedCount', 'rejectedCount', 'rejectedByMeCount', 'isLineManager', 'isGm', 'departments'));
    }

    public function show(Request $request, $id)
    {
        $task = Task::with(['status', 'priority', 'assignedBy', 'assignedTo', 'department', 'logs.logger', 'comments.commenter'])
            ->findOrFail($id);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $task
            ]);
        }

        $statuses = TaskStatus::all();
        return view('emp.tasks.show', compact('task', 'statuses'));
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
            'task_attachments' => 'nullable|array',
            'task_attachments.*' => 'file|max:10240',
            'parent_task_id' => 'nullable|exists:tasks_list,task_id'
        ]);

        $user = Auth::user();
        $employee = $user->employee;
        $employeeId = $employee ? $employee->employee_id : 0;

        // Parse assigned_to input to determine assignment
        $assignedToInput = $request->input('assigned_to');
        $assignedTo = null;
        $departmentId = null;
        $pendingLineManagerId = null;
        $remark = 'Task created — pending line manager assignment';

        if ($assignedToInput === 'myself') {
            // Assign to own list, bypass line manager approval
            $assignedTo = $employeeId;
            $departmentId = null;
            $pendingLineManagerId = null;
            $remark = 'Task created and self-assigned';
        } elseif (str_starts_with($assignedToInput, 'dept_')) {
            // Assign to department directly
            $deptId = (int) substr($assignedToInput, 5); // strip 'dept_'
            $assignedTo = 0;
            $departmentId = $deptId;
            // Route to department's line manager for review & delegation
            $pendingLineManagerId = Department::where('department_id', $deptId)->value('line_manager_id');
            if (!$pendingLineManagerId) {
                // Fallback: if department has no line manager, get general line manager
                $pendingLineManagerId = Department::whereNotNull('line_manager_id')
                    ->value('line_manager_id');
            }
            // If the creator IS the line manager of the assigned department, bypass approval
            if ($pendingLineManagerId && $pendingLineManagerId == $employeeId) {
                $pendingLineManagerId = null;
                $remark = 'Task created and assigned to department (self-approved)';
            } else {
                $remark = 'Task created and assigned to department — pending department manager assignment';
            }
        } else {
            // Fallback (or if it's numeric employee ID)
            $assignedTo = $request->filled('assigned_to') ? (int) $request->assigned_to : null;
            $departmentId = null;
            // Standard flow: goes to the creator's line manager
            if ($employee && $employee->department_id) {
                $pendingLineManagerId = Department::where('department_id', $employee->department_id)
                    ->value('line_manager_id');
            }
            if (!$pendingLineManagerId) {
                $pendingLineManagerId = Department::whereNotNull('line_manager_id')
                    ->value('line_manager_id');
            }
            if ($pendingLineManagerId && $pendingLineManagerId == $employeeId) {
                $pendingLineManagerId = null;
                $remark = 'Task created (self-approved)';
            }
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
        $task->assigned_to = $assignedTo;
        $task->department_id = $departmentId;
        $task->pending_line_manager_id = $pendingLineManagerId;
        $task->priority_id = $request->priority_id;
        $task->parent_task_id = $request->parent_task_id ?? 0;

        if ($request->hasFile('task_attachments')) {
            $paths = [];
            foreach ($request->file('task_attachments') as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/tasks'), $filename);
                $paths[] = 'uploads/tasks/' . $filename;
            }
            $task->task_attachment = json_encode($paths);
        }

        $firstStatus = TaskStatus::orderBy('status_id')->first();
        $task->status_id = $firstStatus ? $firstStatus->status_id : 1;

        $task->save();

        // Notify line manager
        if ($pendingLineManagerId) {
            \App\Services\NotificationService::send(
                "A new task requires your review & assignment: " . $task->task_title,
                "emp/tasks/pending",
                $pendingLineManagerId
            );
        }

        // Initial log
        SystemLog::create([
            'log_action' => 'Task_Added',
            'log_remark' => $remark,
            'related_table' => 'tasks_list',
            'related_id' => $task->task_id,
            'log_date' => now(),
            'logged_by' => $employeeId,
            'logger_type' => 'employees_list',
            'log_type' => 'int'
        ]);

        if ($pendingLineManagerId) {
            return response()->json(['success' => true, 'message' => 'Task submitted to your line manager for assignment!']);
        } else {
            return response()->json(['success' => true, 'message' => 'Task created successfully!']);
        }
    }

    /**
     * List tasks pending this employee as line manager.
     */
    public function pendingTasks(Request $request)
    {
        $employeeId = Auth::user()->employee ? Auth::user()->employee->employee_id : 0;
        $deptId = Auth::user()->employee ? Auth::user()->employee->department_id : null;
        $lineManagerStaff = Employee::where('is_deleted', 0)
            ->when($deptId, fn($q) => $q->where('department_id', $deptId))->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })
            ->orderBy('first_name')->get();
        $tasks = Task::with(['status', 'priority', 'assignedBy', 'assignedTo'])
            ->where('pending_line_manager_id', $employeeId)
            ->orderBy('task_id', 'desc')
            ->get();

        $employees = Employee::where('is_deleted', 0)->whereHas('systemUser', function($q) {
                $q->where('is_active', 1);
            })->orderBy('first_name')->get();

        $permissions = Permission::with(['employee', 'status'])
            ->where('line_manager_id', $employeeId)
            ->whereIn('permission_status_id', [1, 2])
            ->orderBy('permission_id', 'desc')
            ->get();

        return view('emp.tasks.pending_assignments', compact('tasks', 'employees', 'lineManagerStaff', 'permissions'));
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

        $employee = Employee::findOrFail($request->assigned_to);
        if ($task->department_id && $employee->department_id != $task->department_id) {
            return response()->json([
                'success' => false,
                'message' => 'The selected employee does not belong to the department assigned to this task.'
            ], 422);
        }

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
            $lineManagerId = Department::whereNotNull('line_manager_id')->value('line_manager_id');
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

        // Deletion specific validations
        if ($request->has('status_id') && $request->status_id == 5) {
            if ($task->assigned_by != Auth::user()->employee->employee_id) {
                return response()->json(['success' => false, 'message' => 'Only the creator can delete this task.']);
            }
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

        $query = Task::with([
            'status',
            'priority',
            'assignedBy',
            'assignedTo',
            'department',
            'subtasks.status',
            'subtasks.priority',
            'subtasks.assignedBy',
            'subtasks.assignedTo',
            'subtasks.department'
        ]);

        // ── Apply view-mode specific filters ─────────────────────────
        $query = Task::with([
            'status',
            'priority',
            'assignedBy',
            'assignedTo',
            'department',
            'subtasks' => function ($q) {
                $q->where(function ($sq) {
                    $sq->whereNull('pending_line_manager_id')->orWhere('pending_line_manager_id', 0);
                })->where('is_rejected', 0);
            },
            'subtasks.status',
            'subtasks.priority',
            'subtasks.assignedBy',
            'subtasks.assignedTo',
            'subtasks.department'
        ]);

        // ── Apply view-mode specific filters ─────────────────────────
        switch ($viewMode) {

            case 'submitted':
                // Tasks created by this user that are pending LM approval
                $query->where('assigned_by', $employeeId)
                    ->whereNotNull('pending_line_manager_id')
                    ->where('pending_line_manager_id', '!=', 0)
                    ->where('is_rejected', 0)
                    ->where('status_id', '!=', 5);
                break;

            case 'rejected':
                // Tasks created or received by this user that were rejected
                $query->where(function ($q) use ($employeeId) {
                    $q->where('assigned_by', $employeeId)
                        ->orWhere('assigned_to', $employeeId);
                })
                    ->where('is_rejected', 1);
                break;

            case 'rejected_by_me':
                // Tasks this user (as line manager) rejected
                $deptIds = Department::where('line_manager_id', $employeeId)->pluck('department_id');
                $query->where('is_rejected', 1)
                    ->whereHas('assignedBy', fn($q) => $q->whereIn('department_id', $deptIds));
                break;

            case 'pending':
                // Tasks pending THIS user's LM approval
                $query->where('pending_line_manager_id', $employeeId)
                    ->where('is_rejected', 0);
                break;

            case 'others_tasks':
                // Tasks assigned BY this user (approved, not pending)
                $query->where('assigned_by', $employeeId)
                    ->where(function ($q) {
                        $q->whereNull('pending_line_manager_id')
                            ->orWhere('pending_line_manager_id', 0);
                    })
                    ->where('is_rejected', 0);
                break;

            case 'gm_overview':
                // GM-only: all active tasks across all departments
                $query->where(function ($q) {
                        $q->whereNull('pending_line_manager_id')
                            ->orWhere('pending_line_manager_id', 0);
                    })
                    ->where('is_rejected', 0);
                
                $filterDept = $request->input('dept_id');
                if ($filterDept) {
                    $query->where(function($q) use ($filterDept) {
                        $q->where('department_id', $filterDept)
                          ->orWhereHas('assignedTo', fn($sq) => $sq->where('department_id', $filterDept));
                    });
                }
                break;


            default: // 'my_tasks'
                $query->where('assigned_to', $employeeId)
                    ->where(function ($q) {
                        $q->whereNull('pending_line_manager_id')
                            ->orWhere('pending_line_manager_id', 0);
                    })
                    ->where('is_rejected', 0);
                break;
        }

        // Parent task filter
        $query->where(function ($q) {
            $q->whereNull('parent_task_id')->orWhere('parent_task_id', 0);
        });

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
