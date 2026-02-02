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
}
