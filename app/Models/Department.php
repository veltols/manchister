<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'employees_list_departments';
    protected $primaryKey = 'department_id';
    public $timestamps = false;

    protected $guarded = [];

    public function designations()
    {
        return $this->hasMany(Designation::class, 'department_id', 'department_id');
    }

    public function mainDepartment()
    {
        return $this->belongsTo(Department::class, 'main_department_id', 'department_id');
    }

    public function lineManager()
    {
        return $this->belongsTo(Employee::class, 'line_manager_id', 'employee_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'main_department_id', 'department_id');
    }
}
