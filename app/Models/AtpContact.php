<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtpContact extends Model
{
    use HasFactory;

    protected $table = 'atps_list_contacts';
    protected $primaryKey = 'contact_id';
    public $timestamps = false;

    protected $guarded = [];

    public function atp()
    {
        return $this->belongsTo(Atp::class, 'atp_id', 'atp_id');
    }
}
