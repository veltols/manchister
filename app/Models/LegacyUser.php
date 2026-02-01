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
    public $timestamps = false;

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
