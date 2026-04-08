<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'incident_types';
    protected $primaryKey = 'incident_type_id';

    protected $guarded = [];
}
