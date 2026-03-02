<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeService extends Model
{
    protected $table = 'employees_services';
    public $timestamps = false;
    public $incrementing = false;
    // Composite PK: (employee_id, service_id) — no single PK column
    protected $primaryKey = null;

    protected $fillable = [
        'employee_id',
        'service_id',
        'added_by',
        'added_date',
    ];
}

