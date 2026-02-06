<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrLeave extends Model
{
    use HasFactory;

    protected $table = 'hr_employees_leaves';
    protected $primaryKey = 'leave_id';
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'submission_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function type()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'leave_type_id');
    }

    public function approvals()
    {
        return $this->hasMany(HrApproval::class, 'related_id', 'leave_id')
            ->where('related_table', 'hr_leaves');
    }

    public function latestLog()
    {
        return $this->hasOne(SystemLog::class, 'related_id', 'leave_id')
            ->where('related_table', 'hr_employees_leaves')
            ->orderBy('log_date', 'desc');
    }
}
