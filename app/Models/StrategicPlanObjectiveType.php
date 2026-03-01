<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicPlanObjectiveType extends Model
{
    use HasFactory;

    protected $table = 'm_strategic_plans_objective_types';
    protected $primaryKey = 'objective_type_id';
    public $timestamps = false;
    protected $guarded = [];
}
