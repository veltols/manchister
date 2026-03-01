<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicPlanInternalMap extends Model
{
    use HasFactory;

    protected $table = 'm_strategic_plans_internal_maps';
    protected $primaryKey = 'local_map_id';
    public $timestamps = false;
    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function objective()
    {
        return $this->belongsTo(StrategicPlanObjective::class, 'objective_id', 'objective_id');
    }

    public function tasks()
    {
        return $this->hasMany(StrategicPlanInternalMapTask::class, 'local_map_id', 'local_map_id');
    }
}
