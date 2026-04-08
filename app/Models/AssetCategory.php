<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'z_assets_list_cats';
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    protected $guarded = [];
}
