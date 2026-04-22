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
    // Flow: PENDING_APPROVAL (LM queue) → PENDING_GM (GM queue) → APPROVED / REJECTED
    const STATUS_PENDING          = 1;   // No manager — sent to HR
    const STATUS_PENDING_APPROVAL = 2;   // Waiting Line Manager decision
    const STATUS_APPROVED         = 3;   // GM approved (final)
    const STATUS_REJECTED         = 4;   // Rejected by LM or GM
    const STATUS_PENDING_GM       = 5;   // LM approved — waiting GM final decision
    const STATUS_ACTION_REQUIRED  = 6;   // Sent back to employee for action

    // Action Triggers (UI specific)
    const ACTION_SEND_FOR_APPROVAL = 100;
    const ACTION_SEND_BACK         = 200;

    protected $guarded = [];

    protected $casts = [
        'submission_date'  => 'date',
        'start_date'       => 'date',
        'end_date'         => 'date',
        'lm_reviewed_at'   => 'datetime',
        'gm_reviewed_at'   => 'datetime',
        // Cast numeric IDs/status as integers so === comparisons always work
        'leave_status_id'  => 'integer',
        'line_manager_id'  => 'integer',
        'gm_id'            => 'integer',
        'total_days'       => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function lineManager()
    {
        return $this->belongsTo(Employee::class, 'line_manager_id', 'employee_id');
    }

    public function gm()
    {
        return $this->belongsTo(User::class, 'gm_id', 'user_id');
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
