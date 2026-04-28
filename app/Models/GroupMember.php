<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $table = 'z_groups_list_members';
    protected $primaryKey = 'record_id';
    public $timestamps = false;
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function role()
    {
        return $this->belongsTo(GroupRole::class, 'group_role_id', 'group_role_id');
    }
}
