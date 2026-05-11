<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationAttachment extends Model
{
    use HasFactory;

    protected $table = 'outbound_communication_attachments';
    protected $primaryKey = 'attachment_id';
    protected $guarded = [];

    public function communication()
    {
        return $this->belongsTo(CommunicationRequest::class, 'communication_id', 'communication_id');
    }
}
