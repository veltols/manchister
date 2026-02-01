<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProviderLearner extends Model
{
    use HasFactory;

    protected $table = 'atps_list_le';
    protected $primaryKey = 'le_id';
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'submission_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function qualification()
    {
        return $this->belongsTo(TrainingProviderQualification::class, 'qualification_id', 'qualification_id');
    }
}
