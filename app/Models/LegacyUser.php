<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\EmployeeNotification;
use App\Models\Message;

class LegacyUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users_list';
    protected $primaryKey = 'record_id';
    public $timestamps = false;

    protected $guarded = [];

    // Relationship to legacy notifications
    public function internal_notifications()
    {
        return $this->hasMany(EmployeeNotification::class, 'employee_id', 'user_id');
    }

    // Attribute for unread messages count
    public function getUnreadMessagesCountAttribute()
    {
        $userId = $this->user_id;
        return Message::whereHas('conversation', function($q) use ($userId) {
            $q->where('a_id', $userId)->orWhere('b_id', $userId);
        })
        ->where('added_by', '!=', $userId)
        ->where('is_read', 0)
        ->count();
    }

    // Map 'user_id' to the actual entity (Employee or ATP)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'employee_id');
    }

    public function atp()
    {
        return $this->belongsTo(Atp::class, 'user_id', 'atp_id');
    }
    
    // Override to prevent default password check behavior if we use Auth::attempt
    public function getAuthPassword()
    {
        // This logic is complex because it depends on the type.
        // We will likely handle password verification manually in the Controller.
        return ''; 
    }

    public function getRememberTokenName()
    {
        return ''; // Disable remember token
    }
    
    public function setRememberToken($value)
    {
        // Do nothing
    }
}
