<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrGroupFile extends Model
{
    use HasFactory;

    protected $table = 'z_groups_list_files';
    protected $primaryKey = 'file_id';
    public $timestamps = false;

    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(HrGroup::class, 'group_id', 'group_id');
    }

    public function adder()
    {
        return $this->belongsTo(Employee::class, 'added_by', 'employee_id');
    }
}
