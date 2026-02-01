<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees_list';
    protected $primaryKey = 'employee_id';
    public $timestamps = false;

    protected $guarded = [];

    public function passwordData()
    {
        return $this->hasOne(EmployeePass::class, 'employee_id', 'employee_id');
    }
}
