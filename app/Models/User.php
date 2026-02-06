<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Message;
use App\Models\EmployeeNotification;

/**
 * @property string $user_email
 * @property string $user_type
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $table = 'users_list';
    protected $primaryKey = 'record_id';
    public $timestamps = false;

    protected $fillable = [
        'user_email',
        'user_type',
        'int_ext',
        'user_id',
    ];

    protected $hidden = [];

    // Map 'email' attribute for Laravel Auth
    public function getEmailAttribute()
    {
        return $this->user_email;
    }

    // Relationship to Employee details
    public function employee()
    {
        return $this->hasOne(EmployeesList::class, 'employee_id', 'user_id');
    }

    public function internal_notifications()
    {
        return $this->hasMany(EmployeeNotification::class, 'employee_id', 'user_id');
    }

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

    public function getAuthPassword()
    {
        if ($this->employee && $this->employee->passwordData) {
            return $this->employee->passwordData->pass_value;
        }
        return null;
    }
}
