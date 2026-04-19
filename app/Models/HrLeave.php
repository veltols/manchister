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
    
    // Status Constants
    const STATUS_PENDING = 1;
    const STATUS_PENDING_APPROVAL = 2;
    const STATUS_APPROVED = 3;
    const STATUS_REJECTED = 4;
    const STATUS_ACTION_REQUIRED = 6;

    // Action Triggers (UI specific)
    const ACTION_SEND_FOR_APPROVAL = 100;
    const ACTION_SEND_BACK = 200;

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
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'leave_type_id')->withTrashed();
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

    public function status()
    {
        return $this->belongsTo(LeaveStatus::class, 'leave_status_id', 'leave_status_id');
    }
}
