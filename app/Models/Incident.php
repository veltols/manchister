<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'incidents';
    protected $primaryKey = 'incident_id';

    protected $guarded = [];

    // Relationship to reporter (User)
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by', 'user_id');
    }
}
