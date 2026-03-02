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
        return Message::whereHas('conversation', function ($q) use ($userId) {
            $q->where('a_id', $userId)->orWhere('b_id', $userId);
        })
            ->where('added_by', '!=', $userId)
            ->where('is_read', 0)
            ->count();
    }

    public function getUnreadNotificationsCountAttribute()
    {
        return $this->internal_notifications()->where('is_seen', 0)->count();
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

    /**
     * Return all service_ids enabled for this user (cached per request).
     */
    public function enabledServiceIds(): array
    {
        if (!isset($this->_serviceIds)) {
            $this->_serviceIds = \Illuminate\Support\Facades\DB::table('employees_services')
                ->where('employee_id', $this->user_id)
                ->pluck('service_id')
                ->map(fn($id) => (int) $id)
                ->toArray();
        }
        return $this->_serviceIds;
    }

    /**
     * Check if the user has a specific service permission.
     * Service IDs: 10001=Training Providers, 10002=Strategic Plans,
     *              10003=Operational Planning, 10004=Self Studies, 10005=EQA
     */
    public function hasService(int $serviceId): bool
    {
        return in_array($serviceId, $this->enabledServiceIds());
    }
}
