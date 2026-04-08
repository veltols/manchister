<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Priority extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sys_list_priorities';
    protected $primaryKey = 'priority_id';
    public $timestamps = false;

    protected $guarded = [];
}
