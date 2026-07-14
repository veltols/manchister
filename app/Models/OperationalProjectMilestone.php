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

    public function owner()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
    public function ownerDepartment()
    {
        return $this->belongsTo(Department::class, 'employee_id', 'department_id');
    }
    public function tasks()
{
    return $this->hasMany(Task::class, 'operational_milestone_id', 'milestone_id');
}
}
