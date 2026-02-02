<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicPlanObjective extends Model
{
    use HasFactory;

    protected $table = 'm_strategic_plans_objectives';
    protected $primaryKey = 'objective_id';
    public $timestamps = false;

    protected $guarded = [];
}
