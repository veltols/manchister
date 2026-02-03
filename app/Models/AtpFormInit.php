<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtpFormInit extends Model
{
    use HasFactory;

    protected $table = 'atps_form_init';
    protected $primaryKey = 'form_id';
    public $timestamps = false;

    protected $guarded = [];

    public function atp()
    {
        return $this->belongsTo(Atp::class, 'atp_id', 'atp_id');
    }
}
