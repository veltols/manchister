<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrGroupPost extends Model
{
    use HasFactory;

    protected $table = 'z_groups_list_posts';
    protected $primaryKey = 'post_id';
    public $timestamps = false;

    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(HrGroup::class, 'group_id', 'group_id');
    }

    public function author()
    {
        return $this->belongsTo(Employee::class, 'added_by', 'employee_id');
    }
}
