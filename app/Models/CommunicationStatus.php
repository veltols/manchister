<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationStatus extends Model
{
    use HasFactory;

    protected $table = 'm_communications_list_status';
    protected $primaryKey = 'communication_status_id';
    public $timestamps = false;
    protected $guarded = [];
}
