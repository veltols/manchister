<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackForm extends Model
{
    use HasFactory;

    protected $table = 'feedback_forms';
    protected $primaryKey = 'form_id';
    public $timestamps = false;

    protected $guarded = [];

    public function answers()
    {
        return $this->hasOne(FeedbackAnswer::class, 'form_id', 'form_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
