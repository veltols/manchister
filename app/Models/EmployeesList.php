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

    protected $fillable = [
        'emp_status_id'
    ];

    public function status()
    {
        return $this->belongsTo(UserStatus::class, 'emp_status_id', 'staus_id');
    }

    public function passwordData()
    {
        return $this->hasOne(EmployeesListPass::class, 'employee_id', 'employee_id')
            ->where('is_active', '1')
            ->latest('pass_id');
    }
}
