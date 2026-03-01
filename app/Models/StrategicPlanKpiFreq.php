<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicPlanKpiFreq extends Model
{
    use HasFactory;

    protected $table = 'm_strategic_plans_kpis_freqs';
    protected $primaryKey = 'kpi_frequncy_id';
    public $timestamps = false;
    protected $guarded = [];
}
