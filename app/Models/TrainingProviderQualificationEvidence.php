<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProviderQualificationEvidence extends Model
{
    use HasFactory;

    protected $table = 'atps_list_qualifications_evidence';
    protected $primaryKey = 'record_id';
    public $timestamps = false;

    protected $guarded = [];

    public function qualification()
    {
        return $this->belongsTo(TrainingProviderQualification::class, 'qualification_id', 'qualification_id');
    }
}
