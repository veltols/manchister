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
        return $this->belongsTo(SupportTicketCategory::class, 'category_id', 'category_id')->withTrashed();
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id', 'priority_id')->withTrashed();
    }

    public function status()
    {
        return $this->belongsTo(SupportTicketStatus::class, 'status_id', 'status_id')->withTrashed();
    }

    public function addedBy()
    {
        return $this->belongsTo(Employee::class, 'added_by', 'employee_id');
    }

    public function employee()
    {
        return $this->addedBy();
    }

    public function logs()
    {
        // Assuming a generic SystemLog model or a specific one. 
        // Core query uses: related_table='support_tickets_list' AND related_id=ticket_id
        return $this->hasMany(SystemLog::class, 'related_id', 'ticket_id')
            ->where('related_table', 'support_tickets_list')
            ->orderBy('log_id', 'desc');
    }

    public function assignedTo()
    {
        return $this->belongsTo(Employee::class, 'assigned_to', 'employee_id');
    }

    public function latestLog()
    {
        return $this->hasOne(SystemLog::class, 'related_id', 'ticket_id')
            ->where('related_table', 'support_tickets_list')
            ->latest('log_id');
    }

    public static function generateReference()
    {
        $yearMonth = now()->format('y') . now()->format('m'); // e.g. "2605"
        $prefix = 'TK-' . $yearMonth; // e.g. "TK-2605"

        // Find the maximum sequence currently in the database
        $latestTicket = self::where('ticket_ref', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(ticket_ref, 8) AS UNSIGNED) DESC')
            ->first();

        $nextSequence = 1;
        if ($latestTicket) {
            $lastRef = $latestTicket->ticket_ref;
            $sequenceStr = substr($lastRef, 7); // TK-YYMM is 7 characters.
            $nextSequence = ((int) $sequenceStr) + 1;
        }

        // Loop to guarantee no duplicates
        do {
            $ref = $prefix . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
            $exists = self::where('ticket_ref', $ref)->exists();
            if ($exists) {
                $nextSequence++;
            }
        } while ($exists);

        return $ref;
    }
}

