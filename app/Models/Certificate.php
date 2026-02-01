<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $table = 'hr_certificates';
    protected $primaryKey = 'certificate_id';
    public $timestamps = false;

    protected $guarded = [];
}
