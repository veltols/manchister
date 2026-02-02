<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $table = 'z_groups_list_members';
    protected $primaryKey = 'member_id';
    public $timestamps = false;
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(EmployeesList::class, 'employee_id', 'employee_id');
    }

    public function role()
    {
        return $this->belongsTo(GroupRole::class, 'role_id', 'role_id');
    }
}
