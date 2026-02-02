<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicPlan extends Model
{
    use HasFactory;

    protected $table = 'm_strategic_plans';
    protected $primaryKey = 'plan_id';
    public $timestamps = false;

    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class, 'plan_level', 'department_id');
    }

    public function themes()
    {
        return $this->hasMany(StrategicPlanTheme::class, 'plan_id', 'plan_id');
    }

    public function objectives()
    {
        return $this->hasMany(StrategicPlanObjective::class, 'plan_id', 'plan_id');
    }
}
