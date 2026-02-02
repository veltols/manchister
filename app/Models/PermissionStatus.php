<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionStatus extends Model
{
    use HasFactory;

    protected $table = 'hr_employees_permissions_status';
    protected $primaryKey = 'permission_status_id';
    public $timestamps = false;

    protected $guarded = [];
}
