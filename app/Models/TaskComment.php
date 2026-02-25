<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $table = 'task_comments';
    protected $primaryKey = 'comment_id';

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function commenter()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'task_id');
    }
}
