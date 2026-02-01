<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atp extends Model
{
    use HasFactory;

    protected $table = 'atps_list';
    protected $primaryKey = 'atp_id';
    public $timestamps = false;

    protected $guarded = [];

    public function passwordData()
    {
        return $this->hasOne(AtpPass::class, 'atp_id', 'atp_id');
    }
}
