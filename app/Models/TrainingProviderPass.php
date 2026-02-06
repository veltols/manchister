<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProviderPass extends Model
{
    use HasFactory;

    protected $table = 'atps_list_pass';
    protected $primaryKey = 'pass_id';
    public $timestamps = false;
    protected $guarded = [];

     public function provider()
    {
        return $this->belongsTo(TrainingProvider::class, 'atp_id', 'atp_id');
    }
}
