<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class LegacyUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users_list';
    protected $primaryKey = 'record_id';
    public $timestamps = false; // Legacy table has no timestamps

    protected $guarded = [];

    // Map 'user_id' to the actual entity (Employee or ATP)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'employee_id');
    }

    public function atp()
    {
        return $this->belongsTo(Atp::class, 'user_id', 'atp_id');
    }
    
    // Disable standard password check as we use custom logic
    public function getAuthPassword() { return ''; }
    public function getRememberTokenName() { return ''; }
    public function setRememberToken($value) {}
}
