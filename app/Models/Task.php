<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks_list';
    protected $primaryKey = 'task_id';
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'task_assigned_date' => 'datetime',
        'task_due_date' => 'datetime',
    ];

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id', 'status_id');
    }

    public function priority()
    {
        return $this->belongsTo(TaskPriority::class, 'priority_id', 'priority_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(Employee::class, 'assigned_by', 'employee_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(Employee::class, 'assigned_to', 'employee_id');
    }

    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_task_id', 'task_id')->orderBy('task_id', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_task_id', 'task_id');
    }

    public function logs()
    {
        return $this->hasMany(SystemLog::class, 'related_id', 'task_id')
            ->where('related_table', 'tasks_list')
            ->orderBy('log_id', 'desc');
    }

    public function pendingLineManager()
    {
        return $this->belongsTo(Employee::class, 'pending_line_manager_id', 'employee_id');
    }

    public function getTimeProgress()
    {
        if (!$this->task_assigned_date || !$this->task_due_date) {
            return 0;
        }

        $start = $this->task_assigned_date->timestamp;
        $end = $this->task_due_date->timestamp;
        $now = now()->timestamp;

        if ($now <= $start) {
            return 0;
        }
        if ($now >= $end) {
            return 100;
        }

        $total = $end - $start;
        $elapsed = $now - $start;

        return ($total > 0) ? round(($elapsed / $total) * 100) : 0;
    }

    public function getCountedTime()
    {
        if (!$this->task_due_date) {
            return 'N/A';
        }

        $now = now();
        $due = $this->task_due_date;

        if ($now > $due) {
            return 'Overdue';
        }

        $diff = $now->diff($due);

        if ($diff->days > 0) {
            return $diff->days . 'd remaining';
        }
        if ($diff->h > 0) {
            return $diff->h . 'h remaining';
        }
        return $diff->i . 'm remaining';
    }
}
