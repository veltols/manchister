<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtpType extends Model
{
    use HasFactory;

    protected $table = 'atps_list_types';
    protected $primaryKey = 'atp_type_id';
    public $timestamps = false;

    protected $guarded = [];
}
