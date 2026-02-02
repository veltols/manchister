<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicStudy extends Model
{
    use HasFactory;

    protected $table = 'm_strategic_studies';
    protected $primaryKey = 'study_id';
    public $timestamps = false; // Assumed

    protected $guarded = [];
}
