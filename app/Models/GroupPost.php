<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPost extends Model
{
    use HasFactory;

    protected $table = 'z_groups_list_posts';
    protected $primaryKey = 'post_id';
    public $timestamps = false;
    protected $guarded = [];

    public function sender()
    {
        return $this->belongsTo(EmployeesList::class, 'added_by', 'employee_id');
    }
}
