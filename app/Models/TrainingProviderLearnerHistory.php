<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProviderLearnerHistory extends Model
{
    use HasFactory;

    protected $table = 'atps_learner_enrolled';
    protected $primaryKey = 'record_id';
    public $timestamps = false;

    protected $guarded = [];
}
