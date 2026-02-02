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
}
