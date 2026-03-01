<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicPlanTheme extends Model
{
    use HasFactory;

    protected $table = 'm_strategic_plans_themes';
    protected $primaryKey = 'theme_id';
    public $timestamps = false;

    protected $guarded = [];

    public function plan()
    {
        return $this->belongsTo(StrategicPlan::class, 'plan_id', 'plan_id');
    }

    public function objectives()
    {
        return $this->hasMany(StrategicPlanObjective::class, 'theme_id', 'theme_id')->orderBy('order_no');
    }
}
