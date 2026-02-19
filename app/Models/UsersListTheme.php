<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersListTheme extends Model
{
    use HasFactory;

    protected $table = 'users_list_themes';
    protected $primaryKey = 'user_theme_id';
    public $timestamps = false; // Assuming no timestamps as per inspection, verify later or add if needed. Check inspect output again if needed. Inspection showed 7 columns, no created_at/updated_at.

    protected $fillable = [
        'theme_name',
        'color_primary',
        'color_on_primary',
        'color_secondary',
        'color_on_secondary',
        'color_third',
        'color_on_third',
        'is_deleted'
    ];
}
