<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    use HasFactory;

    protected $table = 'sys_list_priorities';
    protected $primaryKey = 'priority_id';
    public $timestamps = false;

    protected $guarded = [];
}
