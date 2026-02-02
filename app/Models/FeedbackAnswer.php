<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackAnswer extends Model
{
    use HasFactory;

    protected $table = 'feedback_forms_answers';
    protected $primaryKey = 'record_id';
    public $timestamps = false;

    protected $guarded = [];

    public function form()
    {
        return $this->belongsTo(FeedbackForm::class, 'form_id', 'form_id');
    }
}
