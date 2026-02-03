<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atp extends Model
{
    use HasFactory;

    protected $table = 'atps_list';
    protected $primaryKey = 'atp_id';
    public $timestamps = false; // Using added_date instead of created_at/updated_at

    protected $guarded = [];

    public function status()
    {
        return $this->belongsTo(AtpStatus::class, 'atp_status_id', 'atp_status_id');
    }

    public function category()
    {
        return $this->belongsTo(AtpCategory::class, 'atp_category_id', 'atp_category_id');
    }

    public function type()
    {
        return $this->belongsTo(AtpType::class, 'atp_type_id', 'atp_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'added_by', 'employee_id');
    }

    public function emirate()
    {
        return $this->belongsTo(City::class, 'emirate_id', 'city_id');
    }

    public function contacts()
    {
        return $this->hasMany(AtpContact::class, 'atp_id', 'atp_id');
    }
}
