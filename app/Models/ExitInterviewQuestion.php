<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitInterviewQuestion extends Model
{
    use HasFactory;

    protected $table = 'hr_exit_interviews_questions';
    protected $primaryKey = 'question_id';
    public $timestamps = false;

    protected $guarded = [];
}
