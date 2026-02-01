<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePass extends Model
{
    use HasFactory;

    protected $table = 'employees_list_pass';
    protected $primaryKey = 'pass_id';
    public $timestamps = false;

    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
