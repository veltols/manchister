<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProviderFaculty extends Model
{
    use HasFactory;

    protected $table = 'atps_list_faculties';
    protected $primaryKey = 'faculty_id';
    public $timestamps = false; // Legacy table

    protected $guarded = [];

    // Helper to get readable type
    public function getTypeNameAttribute()
    {
        $types = [
            1 => 'IQA',
            2 => 'IQA Lead',
            3 => 'Assessor',
            4 => 'Trainer',
            5 => 'Teacher'
        ];
        return $types[$this->faculty_type] ?? 'Unknown';
    }
}
