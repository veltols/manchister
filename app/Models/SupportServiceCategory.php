<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportServiceCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ss_list_cats';
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    protected $guarded = [];

    public function receiver()
    {
        return $this->belongsTo(Employee::class, 'destination_id', 'employee_id');
    }
}
