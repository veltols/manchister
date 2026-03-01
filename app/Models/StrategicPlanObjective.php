<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicPlanObjective extends Model
{
    use HasFactory;

    protected $table = 'm_strategic_plans_objectives';
    protected $primaryKey = 'objective_id';
    public $timestamps = false;
    protected $guarded = [];

    public function theme()
    {
        return $this->belongsTo(StrategicPlanTheme::class, 'theme_id', 'theme_id');
    }

    public function plan()
    {
        return $this->belongsTo(StrategicPlan::class, 'plan_id', 'plan_id');
    }

    public function type()
    {
        return $this->belongsTo(StrategicPlanObjectiveType::class, 'objective_type_id', 'objective_type_id');
    }

    public function kpis()
    {
        return $this->hasMany(StrategicPlanKpi::class, 'objective_id', 'objective_id');
    }

    public function milestones()
    {
        return $this->hasMany(StrategicPlanMilestone::class, 'objective_id', 'objective_id');
    }
}
