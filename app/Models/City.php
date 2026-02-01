<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'sys_countries_cities';
    protected $primaryKey = 'city_id';
    public $timestamps = false;

    protected $guarded = [];
}
