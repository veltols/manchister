<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicPlanKpi extends Model
{
    use HasFactory;

    protected $table = 'm_strategic_plans_kpis';
    protected $primaryKey = 'kpi_id';
    public $timestamps = false;
    protected $guarded = [];

    public function objective()
    {
        return $this->belongsTo(StrategicPlanObjective::class, 'objective_id', 'objective_id');
    }

    public function milestones()
    {
        return $this->hasMany(StrategicPlanMilestone::class, 'kpi_id', 'kpi_id');
    }

    public function frequency()
    {
        return $this->belongsTo(StrategicPlanKpiFreq::class, 'kpi_frequncy_id', 'kpi_frequncy_id');
    }
}
