<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InboundAttachment extends Model
{
    use HasFactory;

    protected $table = 'inbound_attachments';
    protected $primaryKey = 'attachment_id';

    protected $fillable = [
        'inbound_id', 'file_name', 'file_path', 'file_type', 'uploaded_by',
    ];

    public function correspondence()
    {
        return $this->belongsTo(InboundCorrespondence::class, 'inbound_id', 'inbound_id');
    }
}
