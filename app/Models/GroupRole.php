<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupRole extends Model
{
    use HasFactory;

    protected $table = 'z_groups_list_roles';
    protected $primaryKey = 'role_id';
    public $timestamps = false;
    protected $guarded = [];
}
