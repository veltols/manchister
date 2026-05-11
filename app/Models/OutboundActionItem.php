<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutboundActionItem extends Model
{
    use HasFactory;

    protected $table = 'outbound_action_items';
    protected $primaryKey = 'action_id';
    protected $guarded = [];

    public function communication()
    {
        return $this->belongsTo(CommunicationRequest::class, 'communication_id', 'communication_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(Employee::class, 'assigned_to_id', 'employee_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(Employee::class, 'assigned_by_id', 'employee_id');
    }
}
