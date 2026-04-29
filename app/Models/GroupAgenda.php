<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupAgenda extends Model
{
    protected $table = 'z_groups_list_agendas';
    protected $primaryKey = 'agenda_id';

    protected $fillable = [
        'group_id',
        'added_by',
        'title',
        'description',
        'priority',
        'status',
        'start_date',
        'time_duration',
        'end_date',
        'decision_outcome',
        'action_items',
    ];

    public function group()
    {
        return $this->belongsTo(HrGroup::class, 'group_id', 'group_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'added_by', 'employee_id');
    }
}
