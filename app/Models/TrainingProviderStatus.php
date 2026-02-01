<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProviderStatus extends Model
{
    use HasFactory;

    protected $table = 'atps_list_status';
    protected $primaryKey = 'atp_status_id';
    public $timestamps = false; // Legacy

    protected $guarded = [];
}
