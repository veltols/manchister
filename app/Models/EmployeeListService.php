<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeListService extends Model
{
    protected $table = 'employees_list_services';
    protected $primaryKey = 'service_id';
    public $timestamps = false;

    protected $guarded = [];

    /**
     * The employees that have this service enabled.
     */
    public function employees()
    {
        return $this->belongsToMany(
            Employee::class,
            'employees_services',
            'service_id',
            'employee_id'
        );
    }
}
