<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePermission extends Model
{
    use HasFactory;

    protected $table = 'hr_employees_permissions';
    protected $primaryKey = 'permission_id';
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'submission_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    // Status is likely hardcoded in legacy or small table, assume table
    public function status()
    {
        return $this->belongsTo(LeavePermissionStatus::class, 'permission_status_id', 'permission_status_id');
    }
}
