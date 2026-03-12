<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketStatus extends Model
{
    use HasFactory;

    protected $table = 'support_tickets_list_status';
    protected $primaryKey = 'status_id';
    public $timestamps = false;

    protected $guarded = [];

    // Constants for statuses
    const OPEN = 1;
    const IN_PROGRESS = 2;
    const RESOLVED = 3;
    const CANCELLED = 4;

    /**
     * Get status ID by name to avoid static IDs in controllers.
     */
    public static function getIdByName($name)
    {
        return self::where('status_name', $name)->value('status_id');
    }
}
