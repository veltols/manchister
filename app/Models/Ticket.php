<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'support_tickets_list';
    protected $primaryKey = 'ticket_id';
    public $timestamps = false;

    protected $guarded = [];

    // Relationships
    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'category_id', 'category_id');
    }

    public function priority()
    {
        return $this->belongsTo(TaskPriority::class, 'priority_id', 'priority_id'); // Using TaskPriority if shared or similar name
    }

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id', 'status_id'); // Likely shares status/priority tables or similar structure
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'added_by', 'employee_id');
    }
}
