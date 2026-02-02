<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplinaryActionWarning extends Model
{
    use HasFactory;

    protected $table = 'hr_disp_actions_warnings';
    protected $primaryKey = 'da_warning_id';
    public $timestamps = false;
    protected $guarded = [];
}
