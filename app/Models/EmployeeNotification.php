<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeNotification extends Model
{
    use HasFactory;

    protected $table = 'employees_notifications';
    protected $primaryKey = 'notification_id';
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'is_seen' => 'boolean',
        'notification_date' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
