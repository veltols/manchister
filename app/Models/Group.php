<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'z_groups_list';
    protected $primaryKey = 'group_id';
    public $timestamps = false;

    protected $guarded = [];

    public function members()
    {
        return $this->hasMany(GroupMember::class, 'group_id', 'group_id');
    }

    public function posts()
    {
        return $this->hasMany(GroupPost::class, 'group_id', 'group_id')->orderBy('post_id', 'desc');
    }

    public function files()
    {
        return $this->hasMany(GroupFile::class, 'group_id', 'group_id')->orderBy('file_id', 'desc');
    }
}
