<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveStatus extends Model
{
    protected $table = 'hr_employees_leave_status';
    protected $primaryKey = 'leave_status_id';
    public $timestamps = false;
    
    protected $guarded = [];
}
