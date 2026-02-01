<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProviderEQA extends Model
{
    use HasFactory;

    protected $table = 'atps_eqa_details';
    protected $primaryKey = 'eqa_id';
    
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'eqa_visit_date' => 'date',
        'added_date' => 'datetime',
        'submitted_date' => 'datetime',
    ];
}
