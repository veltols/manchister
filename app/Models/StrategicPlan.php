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
        return $this->hasMany(StrategicPlanTheme::class, 'plan_id', 'plan_id')->orderBy('order_no');
    }

    public function objectives()
    {
        return $this->hasMany(StrategicPlanObjective::class, 'plan_id', 'plan_id');
    }

    public function kpis()
    {
        return $this->hasMany(StrategicPlanKpi::class, 'plan_id', 'plan_id');
    }

    public function milestones()
    {
        return $this->hasMany(StrategicPlanMilestone::class, 'plan_id', 'plan_id');
    }

    public function externalMaps()
    {
        return $this->hasMany(StrategicPlanExternalMap::class, 'plan_id', 'plan_id');
    }

    public function internalMaps()
    {
        return $this->hasMany(StrategicPlanInternalMap::class, 'plan_id', 'plan_id');
    }
}
