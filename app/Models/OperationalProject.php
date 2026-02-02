<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationalProject extends Model
{
    use HasFactory;

    protected $table = 'm_operational_projects';
    protected $primaryKey = 'project_id';
    public $timestamps = false; // Assumed

    protected $guarded = [];

    public function plan()
    {
        return $this->belongsTo(StrategicPlan::class, 'plan_id', 'plan_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
}
