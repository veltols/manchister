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
}
