<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'employees_list_departments';
    protected $primaryKey = 'department_id';
    public $timestamps = false; // Legacy table

    protected $guarded = [];

    public function lineManager()
    {
        return $this->belongsTo(Employee::class, 'line_manager_id', 'employee_id');
    }

    public function mainDepartment()
    {
        return $this->belongsTo(Department::class, 'main_department_id', 'department_id');
    }

    public function subDepartments()
    {
        return $this->hasMany(Department::class, 'main_department_id', 'department_id');
    }
}
