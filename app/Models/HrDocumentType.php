<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrDocumentType extends Model
{
    use HasFactory;

    protected $table = 'hr_documents_types';
    protected $primaryKey = 'document_type_id';
    public $timestamps = false;

    protected $guarded = [];

    public function documents()
    {
        return $this->hasMany(HrDocument::class, 'document_type_id', 'document_type_id');
    }
}
