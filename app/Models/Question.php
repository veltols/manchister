<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    // Assuming table name from legacy context
    protected $table = 'questions_list';
    protected $primaryKey = 'question_id'; // Guessing PK
    public $timestamps = false;

    protected $guarded = [];

    public function adder()
    {
        return $this->belongsTo(Employee::class, 'added_by', 'employee_id');
    }
}
