<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProvider extends Model
{
    use HasFactory;

    protected $table = 'atps_list';
    protected $primaryKey = 'atp_id';
    public $timestamps = false;

    protected $guarded = [];

    public function status()
    {
        return $this->belongsTo(TrainingProviderStatus::class, 'atp_status_id', 'atp_status_id');
    }

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'added_by', 'employee_id');
    }

    public function category()
    {
        return $this->belongsTo(TrainingProviderCategory::class, 'atp_category_id', 'atp_category_id');
    }

    public function type()
    {
        return $this->belongsTo(TrainingProviderType::class, 'atp_type_id', 'atp_type_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'emirate_id', 'city_id');
    }

    public function contacts()
    {
        return $this->hasMany(TrainingProviderContact::class, 'atp_id', 'atp_id');
    }

    public function faculty()
    {
        return $this->hasMany(TrainingProviderFaculty::class, 'atp_id', 'atp_id');
    }

    public function learners()
    {
        return $this->hasMany(TrainingProviderLearner::class, 'atp_id', 'atp_id');
    }

    public function qualifications()
    {
        return $this->hasMany(TrainingProviderQualification::class, 'atp_id', 'atp_id');
    }

    public function eqa()
    {
        return $this->hasOne(TrainingProviderEQA::class, 'atp_id', 'atp_id');
    }

    public function sed()
    {
        return $this->hasOne(TrainingProviderSED::class, 'atp_id', 'atp_id');
    }

    public function qip()
    {
        return $this->hasMany(AtpCompliance::class, 'atp_id', 'atp_id')->where('answer', 3);
    }

    public function locations()
    {
        return $this->hasMany(TrainingProviderLocation::class, 'atp_id', 'atp_id');
    }

    public function learnerHistory()
    {
        return $this->hasOne(TrainingProviderLearnerHistory::class, 'atp_id', 'atp_id');
    }
}
