<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProviderContact extends Model
{
    use HasFactory;

    protected $table = 'atps_list_contacts';
    protected $primaryKey = 'contact_id'; // Assuming contact_id or id, legacy usually uses record_id or similar, let's assume contact_id or check valid assumption if keys are not auto. Legacy uses auto increment usually.
    // Checking serv_new.php: it doesn't specify primary key insert, so it's auto.
    public $timestamps = false;

    protected $guarded = [];
}
