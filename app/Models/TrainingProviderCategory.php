<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProviderCategory extends Model
{
    use HasFactory;

    protected $table = 'atps_list_categories';
    protected $primaryKey = 'atp_category_id';
    public $timestamps = false;

    protected $guarded = [];
}
