<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitInterviewAnswer extends Model
{
    use HasFactory;

    protected $table = 'hr_exit_interviews_answers';
    protected $primaryKey = 'answer_id';
    public $timestamps = false;

    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(ExitInterviewQuestion::class, 'question_id', 'question_id');
    }
}
