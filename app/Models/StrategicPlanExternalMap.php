<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicPlanExternalMap extends Model
{
    use HasFactory;

    protected $table = 'm_strategic_plans_external_maps';
    protected $primaryKey = 'record_id';
    public $timestamps = false;
    protected $guarded = [];

    public function objective()
    {
        return $this->belongsTo(StrategicPlanObjective::class, 'objective_id', 'objective_id');
    }
}
