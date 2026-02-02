<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    protected $table = 'employees_list_designations';
    protected $primaryKey = 'designation_id';
    public $timestamps = false;

    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
}
