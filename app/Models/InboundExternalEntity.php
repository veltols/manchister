<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InboundExternalEntity extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inbound_external_entities';
    protected $primaryKey = 'entity_id';

    protected $fillable = [
        'entity_name', 'entity_code', 'entity_email', 'entity_phone', 'is_active',
    ];

    public function correspondences()
    {
        return $this->hasMany(InboundCorrespondence::class, 'entity_id', 'entity_id');
    }
}
