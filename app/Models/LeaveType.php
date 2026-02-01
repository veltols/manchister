<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $table = 'hr_employees_leave_types';
    protected $primaryKey = 'leave_type_id';
    public $timestamps = false; // Assuming legacy table doesn't have timestamps

    protected $guarded = [];
}
