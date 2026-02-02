<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'hr_attendance';
    protected $primaryKey = 'attendance_id';
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'checkin_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeesList::class, 'employee_id', 'employee_id');
    }
}
