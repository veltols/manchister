<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees_list';
    protected $primaryKey = 'employee_id';
    public $timestamps = false; // Assumed based on schema lacking created_at/updated_at standard

    protected $guarded = [];

    public function passwordData()
    {
        return $this->hasOne(EmployeesListPass::class, 'employee_id', 'employee_id')
            ->where('is_active', 1);
    }

    public function systemUser()
    {
        return $this->hasOne(User::class, 'user_id', 'employee_id');
    }

    public function getIsActiveAttribute()
    {
        return $this->systemUser ? $this->systemUser->is_active : 0;
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'designation_id');
    }

    public function credentials()
    {
        return $this->hasOne(EmployeeCred::class, 'employee_id', 'employee_id');
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->second_name} {$this->third_name} {$this->last_name}");
    }

    public function leaves()
    {
        return $this->hasMany(HrLeave::class, 'employee_id', 'employee_id');
    }

    public function permissions()
    {
        return $this->hasMany(LeavePermission::class, 'employee_id', 'employee_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'employee_id');
    }

    public function disciplinaryActions()
    {
        return $this->hasMany(DisciplinaryAction::class, 'employee_id', 'employee_id');
    }

    public function performance()
    {
        return $this->hasMany(Performance::class, 'employee_id', 'employee_id');
    }

    public function exitInterviews()
    {
        return $this->hasMany(ExitInterview::class, 'employee_id', 'employee_id');
    }

    public function logs()
    {
        return $this->hasMany(SystemLog::class, 'related_id', 'employee_id')
            ->where('related_table', 'employees_list')
            ->orderBy('log_date', 'desc');
    }

    public function internal_notifications()
    {
        return $this->hasMany(EmployeeNotification::class, 'employee_id', 'employee_id');
    }
}
