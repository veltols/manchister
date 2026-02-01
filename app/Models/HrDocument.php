<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrDocument extends Model
{
    use HasFactory;

    protected $table = 'hr_documents';
    protected $primaryKey = 'document_id';
    public $timestamps = false;

    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(HrDocumentType::class, 'document_type_id', 'document_type_id');
    }
}
