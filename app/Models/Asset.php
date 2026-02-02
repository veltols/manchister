<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'z_assets_list';
    protected $primaryKey = 'asset_id';
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'assigned_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'category_id', 'category_id');
    }


    public function assignee()
    {
        return $this->belongsTo(Employee::class, 'assigned_to', 'employee_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(Employee::class, 'assigned_by', 'employee_id'); // Assuming assigned_by is also an employee_id
    }
}
