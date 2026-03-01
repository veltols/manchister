<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrategicStudyPage extends Model
{
    protected $table      = 'm_strategic_studies_pages';
    protected $primaryKey = 'page_id';
    public    $timestamps = false;
    protected $guarded    = [];

    public function study()
    {
        return $this->belongsTo(StrategicStudy::class, 'study_id', 'study_id');
    }
}
