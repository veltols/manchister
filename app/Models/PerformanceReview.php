<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    use HasFactory;

    protected $table = 'hr_performance';
    protected $primaryKey = 'performance_id';
    public $timestamps = false;

    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function marker()
    {
        return $this->belongsTo(Employee::class, 'added_by', 'employee_id');
    }
}
