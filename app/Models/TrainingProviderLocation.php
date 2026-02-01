<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProviderLocation extends Model
{
    use HasFactory;

    protected $table = 'atps_list_locations';
    protected $primaryKey = 'location_id';
    public $timestamps = false; // Legacy table usually doesn't have standard timestamps unless verified

    protected $guarded = [];
}
