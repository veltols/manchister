<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationalProjectKpi extends Model
{
    protected $table      = 'm_operational_projects_kpis';
    protected $primaryKey = 'kpi_id';
    public    $timestamps = false;
    protected $guarded    = [];

    public function project()
    {
        return $this->belongsTo(OperationalProject::class, 'project_id', 'project_id');
    }

    public function linkedKpi()
    {
        return $this->belongsTo(StrategicPlanKpi::class, 'linked_kpi_id', 'kpi_id');
    }

    public function milestones()
    {
        return $this->hasMany(OperationalProjectMilestone::class, 'kpi_id', 'kpi_id');
    }
}
