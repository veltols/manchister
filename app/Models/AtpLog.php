<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ATPLog extends Model
{
    use HasFactory;

    protected $table = 'atps_list_logs';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $guarded = [];
}
