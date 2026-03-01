<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicPlanMilestone extends Model
{
    use HasFactory;

    protected $table = 'm_strategic_plans_milestones';
    protected $primaryKey = 'milestone_id';
    public $timestamps = false;
    protected $guarded = [];

    public function kpi()
    {
        return $this->belongsTo(StrategicPlanKpi::class, 'kpi_id', 'kpi_id');
    }

    public function objective()
    {
        return $this->belongsTo(StrategicPlanObjective::class, 'objective_id', 'objective_id');
    }
}
