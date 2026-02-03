<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtpProgRegisterReq extends Model
{
    use HasFactory;

    protected $table = 'atps_prog_register_req';
    protected $primaryKey = 'form_id';
    public $timestamps = false;

    protected $guarded = [];

    public function atp()
    {
        return $this->belongsTo(Atp::class, 'atp_id', 'atp_id');
    }
}
