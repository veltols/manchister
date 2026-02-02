<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportServiceCategory extends Model
{
    use HasFactory;

    protected $table = 'ss_list_cats';
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    protected $guarded = [];
}
