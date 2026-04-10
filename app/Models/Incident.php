<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'incidents';
    protected $primaryKey = 'incident_id';

    protected $guarded = [];

    // Relationship to reporter (User)
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by', 'user_id');
    }

    // Relationships to assigned persons (via EmployeesList)
    public function assignedPerson1()
    {
        return $this->belongsTo(EmployeesList::class, 'assigned_person_1', 'employee_id');
    }

    public function assignedPerson2()
    {
        return $this->belongsTo(EmployeesList::class, 'assigned_person_2', 'employee_id');
    }

    public function assignedPerson3()
    {
        return $this->belongsTo(EmployeesList::class, 'assigned_person_3', 'employee_id');
    }
}
