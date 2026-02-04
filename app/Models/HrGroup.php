<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrGroup extends Model
{
    use HasFactory;

    protected $table = 'z_groups_list';
    protected $primaryKey = 'group_id';
    public $timestamps = false;

    protected $guarded = [];

    public function color()
    {
        return $this->belongsTo(SysColor::class, 'group_color_id', 'color_id');
    }

    public function members()
    {
        return $this->hasMany(HrGroupMember::class, 'group_id', 'group_id');
    }

    public function files()
    {
        return $this->hasMany(HrGroupFile::class, 'group_id', 'group_id');
    }

    public function posts()
    {
        return $this->hasMany(HrGroupPost::class, 'group_id', 'group_id');
    }

    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->group_name);
        $initials = '';
        foreach ($words as $w) {
            $initials .= mb_substr($w, 0, 1);
        }
        return mb_strtoupper(mb_substr($initials, 0, 2));
    }
}
