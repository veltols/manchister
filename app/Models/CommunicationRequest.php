<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationRequest extends Model
{
    use HasFactory;

    protected $table = 'm_communications_list';
    protected $primaryKey = 'communication_id';
    public $timestamps = false;

    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(CommunicationType::class, 'communication_type_id', 'communication_type_id');
    }

    public function status()
    {
        return $this->belongsTo(CommunicationStatus::class, 'communication_status_id', 'communication_status_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeesList::class, 'requested_by', 'employee_id');
    }
}
