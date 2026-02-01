<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $table = 'z_groups_list_members';
    // protected $primaryKey = 'id'; // Assuming composite or auto-id, usually belongs to pivot but legacy defined as table
    // Legacy serv_list joins `z_groups_list_members`.`group_id`
    public $timestamps = false;

    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
