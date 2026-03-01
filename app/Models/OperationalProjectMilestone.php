<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationalProjectMilestone extends Model
{
    protected $table      = 'm_operational_projects_milestones';
    protected $primaryKey = 'milestone_id';
    public    $timestamps = false;
    protected $guarded    = [];

    public function project()
    {
        return $this->belongsTo(OperationalProject::class, 'project_id', 'project_id');
    }

    public function kpi()
    {
        return $this->belongsTo(StrategicPlanKpi::class, 'kpi_id', 'kpi_id');
    }

    public function objective()
    {
        return $this->belongsTo(StrategicPlanObjective::class, 'objective_id', 'objective_id');
    }
}
