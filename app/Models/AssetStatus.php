<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetStatus extends Model
{
    use HasFactory;

    protected $table = 'z_assets_list_status';
    protected $primaryKey = 'status_id';
    public $timestamps = false;

    protected $guarded = [];
}
