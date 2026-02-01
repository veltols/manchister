<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetCategory extends Model
{
    use HasFactory;

    protected $table = 'z_assets_list_cats';
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    protected $guarded = [];
}
