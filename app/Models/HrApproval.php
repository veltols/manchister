<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrApproval extends Model
{
    use HasFactory;

    protected $table = 'hr_approvals';
    protected $primaryKey = 'approval_id';
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'sent_date' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(Employee::class, 'added_by', 'employee_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Employee::class, 'sent_to_id', 'employee_id');
    }
}
