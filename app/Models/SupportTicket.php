<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $table = 'support_tickets_list';
    protected $primaryKey = 'ticket_id';
    public $timestamps = false;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(SupportTicketCategory::class, 'category_id', 'category_id');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id', 'priority_id');
    }

    public function status()
    {
        return $this->belongsTo(SupportTicketStatus::class, 'status_id', 'status_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(Employee::class, 'added_by', 'employee_id');
    }

    public function logs()
    {
        // Assuming a generic SystemLog model or a specific one. 
        // Core query uses: related_table='support_tickets_list' AND related_id=ticket_id
        return $this->hasMany(SystemLog::class, 'related_id', 'ticket_id')
            ->where('related_table', 'support_tickets_list')
            ->orderBy('log_id', 'desc');
    }
}
