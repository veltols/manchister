<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplinaryActionType extends Model
{
    use HasFactory;

    protected $table = 'hr_disp_actions_types';
    protected $primaryKey = 'da_type_id';
    public $timestamps = false;

    protected $guarded = [];
}
