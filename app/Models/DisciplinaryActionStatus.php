<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplinaryActionStatus extends Model
{
    use HasFactory;

    protected $table = 'hr_disp_actions_status';
    protected $primaryKey = 'da_status_id';
    public $timestamps = false;

    protected $guarded = [];
}
