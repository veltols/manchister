<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportService extends Model
{
    use HasFactory;

    protected $table = 'ss_list';
    protected $primaryKey = 'ss_id';
    public $timestamps = false;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(SupportServiceCategory::class, 'category_id', 'category_id');
    }

    public function status()
    {
        return $this->belongsTo(SupportServiceStatus::class, 'status_id', 'status_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeesList::class, 'added_by', 'employee_id');
    }

    public function sender()
    {
        return $this->belongsTo(EmployeesList::class, 'added_by', 'employee_id');
    }

    public function receiver()
    {
        return $this->belongsTo(EmployeesList::class, 'sent_to_id', 'employee_id');
    }

    public function logs()
    {
        return $this->hasMany(SystemLog::class, 'related_id', 'ss_id')
            ->where('related_table', 'ss_list')
            ->orderBy('log_id', 'desc');
    }
}
