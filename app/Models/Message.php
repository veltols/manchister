<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'z_messages_list_posts';
    protected $primaryKey = 'post_id';
    public $timestamps = false; // Legacy table has 'added_date' but probably not updated_at

    protected $guarded = [];

    // Relationships
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'chat_id', 'chat_id');
    }

    public function sender()
    {
        return $this->belongsTo(Employee::class, 'added_by', 'employee_id');
    }
}
