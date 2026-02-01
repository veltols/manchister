<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProviderQualification extends Model
{
    use HasFactory;

    protected $table = 'atps_list_qualifications';
    protected $primaryKey = 'qualification_id';
    public $timestamps = false;

    protected $guarded = [];

    public function evidence()
    {
        return $this->hasMany(TrainingProviderQualificationEvidence::class, 'qualification_id', 'qualification_id');
    }

    public function faculty()
    {
        return $this->hasMany(TrainingProviderFaculty::class, 'qualification_id', 'qualification_id');
    }
}
