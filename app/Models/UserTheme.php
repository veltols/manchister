<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTheme extends Model
{
    use HasFactory;

    protected $table = 'users_list_themes';
    protected $primaryKey = 'user_theme_id';
    public $timestamps = false;

    protected $fillable = [
        'color_primary',
        'color_on_primary',
        'color_secondary',
        'color_on_secondary',
        'color_third',
        'color_on_third'
    ];
}
