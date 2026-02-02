<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $table = 'sys_logs'; // Assuming this table name exists in core
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $guarded = [];

    // Optional: relationship to user who logged it
    public function logger()
    {
        // Core stores 'logged_by' as an ID probably
        return $this->belongsTo(Employee::class, 'logged_by', 'employee_id');
    }
}
