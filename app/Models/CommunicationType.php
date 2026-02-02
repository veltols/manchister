<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationType extends Model
{
    use HasFactory;

    protected $table = 'm_communications_list_types';
    protected $primaryKey = 'communication_type_id';
    public $timestamps = false;
    protected $guarded = [];
}
