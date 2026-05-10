<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InboundActionItem extends Model
{
    use HasFactory;

    protected $table = 'inbound_action_items';
    protected $primaryKey = 'action_id';

    protected $fillable = [
        'inbound_id', 'action_type', 'assigned_to', 'action_required',
        'due_date', 'status', 'action_note',
    ];

    public function correspondence()
    {
        return $this->belongsTo(InboundCorrespondence::class, 'inbound_id', 'inbound_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'user_id');
    }

    public function assignedEmployee()
    {
        return $this->belongsTo(Employee::class, 'assigned_to', 'employee_id');
    }
}
