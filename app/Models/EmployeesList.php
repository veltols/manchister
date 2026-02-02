<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeesList extends Model
{
    use HasFactory;

    protected $table = 'employees_list';
    protected $primaryKey = 'employee_id';
    public $timestamps = false;

    public function passwordData()
    {
        return $this->hasOne(EmployeesListPass::class, 'employee_id', 'employee_id');
    }
}
