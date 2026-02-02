<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplinaryAction extends Model
{
    use HasFactory;

    protected $table = 'hr_disp_actions';
    protected $primaryKey = 'da_id';
    public $timestamps = false;

    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(DisciplinaryActionType::class, 'da_type_id', 'da_type_id');
    }

    public function warning()
    {
        return $this->belongsTo(DisciplinaryActionWarning::class, 'da_warning_id', 'da_warning_id');
    }

    public function status()
    {
        return $this->belongsTo(DisciplinaryActionStatus::class, 'da_status_id', 'da_status_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeesList::class, 'employee_id', 'employee_id');
    }
}
