<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ATPStatus extends Model
{
    use HasFactory;

    protected $table = 'atps_list_status';
    protected $primaryKey = 'status_id';
    public $timestamps = false; // Assuming legacy tables don't have standard timestamps

    protected $guarded = [];
}
