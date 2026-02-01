<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $table = 'z_messages_list';
    protected $primaryKey = 'chat_id';
    public $timestamps = false; // Legacy table

    protected $guarded = [];

    // Relationships
    public function participantA()
    {
        return $this->belongsTo(Employee::class, 'a_id', 'employee_id');
    }

    public function participantB()
    {
        return $this->belongsTo(Employee::class, 'b_id', 'employee_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id', 'chat_id');
    }
}
