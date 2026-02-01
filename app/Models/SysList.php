<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysList extends Model
{
    use HasFactory;

    protected $table = 'sys_lists';
    protected $primaryKey = 'item_id';
    public $timestamps = false;

    protected $guarded = [];
}
