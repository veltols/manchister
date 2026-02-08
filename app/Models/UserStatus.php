<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    use HasFactory;

    protected $table = 'employees_list_staus'; // Note the typo in legacy table name
    protected $primaryKey = 'staus_id';
    public $timestamps = false;

    protected $fillable = [
        'staus_name',
        'staus_color'
    ];
}
