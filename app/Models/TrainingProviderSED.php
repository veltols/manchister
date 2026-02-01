<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProviderSED extends Model
{
    use HasFactory;

    protected $table = 'atps_sed_form';
    protected $primaryKey = 'sed_id'; // Assuming sed_id or similar, checking legacy query... selects * where atp_id. Likely auto-increment.
    // If PK is unknown, we can default to 'id' but legacy tables are tricky. I'll rely on relation via atp_id.
    
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'sed_3' => 'date', // Date of last College Internal Audit
        'added_date' => 'datetime',
        'submitted_date' => 'datetime',
    ];
}
