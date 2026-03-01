<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicPlanInternalMapTask extends Model
{
    use HasFactory;

    protected $table = 'm_strategic_plans_internal_maps_tasks';
    protected $primaryKey = 'record_id';
    public $timestamps = false;
    protected $guarded = [];
}
