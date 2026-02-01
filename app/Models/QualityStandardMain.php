<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityStandardMain extends Model
{
    use HasFactory;

    protected $table = 'quality_standard_main';
    protected $primaryKey = 'main_id';
    public $timestamps = false;

    protected $guarded = [];

    public function complianceRecords()
    {
        return $this->hasMany(AtpCompliance::class, 'main_id', 'main_id');
    }
}
