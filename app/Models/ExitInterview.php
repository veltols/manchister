<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitInterview extends Model
{
    use HasFactory;

    protected $table = 'hr_exit_interviews';
    protected $primaryKey = 'interview_id';
    public $timestamps = false;

    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function answers()
    {
        return $this->hasMany(ExitInterviewAnswer::class, 'interview_id', 'interview_id');
    }
}
