<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupFile extends Model
{
    use HasFactory;

    protected $table = 'z_groups_list_files';
    protected $primaryKey = 'file_id';
    public $timestamps = false;

    protected $guarded = [];
}
