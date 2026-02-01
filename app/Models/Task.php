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
}
