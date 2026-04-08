<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunicationType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'm_communications_list_types';
    protected $primaryKey = 'communication_type_id';
    public $timestamps = false;
    protected $guarded = [];

    public function approval1()
    {
        return $this->belongsTo(Employee::class, 'approval_id_1', 'employee_id');
    }

    public function approval2()
    {
        return $this->belongsTo(Employee::class, 'approval_id_2', 'employee_id');
    }
}
