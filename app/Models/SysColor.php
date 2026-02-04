<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysColor extends Model
{
    use HasFactory;

    protected $table = 'sys_lists_colors';
    protected $primaryKey = 'color_id';
    public $timestamps = false;

    protected $guarded = [];
}
