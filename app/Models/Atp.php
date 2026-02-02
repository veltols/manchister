<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ATP extends Model
{
    use HasFactory;

    protected $table = 'atps_list';
    protected $primaryKey = 'atp_id';
    public $timestamps = false;

    protected $guarded = [];

    // Relationships
    public function status()
    {
        return $this->belongsTo(ATPStatus::class, 'status_id', 'status_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(Employee::class, 'added_by', 'employee_id')->withDefault([
            'last_name' => 'Admin'
        ]);
    }

    public function logs()
    {
        return $this->hasMany(ATPLog::class, 'atp_id', 'atp_id')->orderBy('log_date', 'desc');
    }
}
