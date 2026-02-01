<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtpCompliance extends Model
{
    use HasFactory;

    protected $table = 'atp_compliance';
    protected $primaryKey = 'record_id';
    public $timestamps = false;

    protected $guarded = [];

    public function standardMain()
    {
        return $this->belongsTo(QualityStandardMain::class, 'main_id', 'main_id');
    }
}
