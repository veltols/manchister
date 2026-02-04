<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrGroupMember extends Model
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
        return $this->belongsTo(HrGroupRole::class, 'group_role_id', 'group_role_id');
    }

    public function group()
    {
        return $this->belongsTo(HrGroup::class, 'group_id', 'group_id');
    }
}
