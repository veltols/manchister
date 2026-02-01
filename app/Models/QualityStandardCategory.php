<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityStandardCategory extends Model
{
    use HasFactory;

    protected $table = 'quality_standards_cats';
    protected $primaryKey = 'cat_id';
    public $timestamps = false;

    protected $guarded = [];
}
