<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationRequest extends Model
{
    use HasFactory;

    protected $table = 'm_communications_list';
    protected $primaryKey = 'communication_id';
    public $timestamps = false;

    protected $guarded = [];
 
    protected $casts = [
        'requested_date' => 'datetime',
        'approved_1_date' => 'datetime',
    ];

    public function type()
    {
        return $this->belongsTo(CommunicationType::class, 'communication_type_id', 'communication_type_id')->withTrashed();
    }

    public function status()
    {
        return $this->belongsTo(CommunicationStatus::class, 'communication_status_id', 'communication_status_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'requested_by', 'employee_id');
    }

    public function attachments()
    {
        return $this->hasMany(CommunicationAttachment::class, 'communication_id', 'communication_id');
    }

    public function actionItems()
    {
        return $this->hasMany(OutboundActionItem::class, 'communication_id', 'communication_id');
    }
}
