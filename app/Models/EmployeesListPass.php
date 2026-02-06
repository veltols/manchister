<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeesListPass extends Model
{
    use HasFactory;

    protected $table = 'employees_list_pass';
    protected $primaryKey = 'pass_id';
    public $timestamps = false;
}
