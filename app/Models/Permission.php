<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'hr_employees_permissions';
    protected $primaryKey = 'permission_id';
    public $timestamps = false; // Based on core schema

    protected $guarded = [];

    protected $casts = [
        'submission_date' => 'date',
        'start_date' => 'date',
    ];

    public function status()
    {
        return $this->belongsTo(PermissionStatus::class, 'permission_status_id', 'permission_status_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeesList::class, 'employee_id', 'employee_id');
    }
}
