<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InboundCorrespondence extends Model
{
    use HasFactory;

    protected $table = 'inbound_correspondences';
    protected $primaryKey = 'inbound_id';

    protected $fillable = [
        'reference_code', 'correspondence_type', 'entity_id', 'date_of_receipt',
        'priority', 'confidentiality_level', 'mode_of_receipt',
        'subject', 'description', 'purpose', 'status',
        'digitization_status', 'gm_comments', 'registered_by', 'gm_user_id',
    ];

    public function entity()
    {
        return $this->belongsTo(InboundExternalEntity::class, 'entity_id', 'entity_id');
    }

    public function registeredBy()
    {
        // registered_by stores user_id from users_list
        return $this->belongsTo(User::class, 'registered_by', 'user_id');
    }

    public function gmUser()
    {
        return $this->belongsTo(User::class, 'gm_user_id', 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(InboundAttachment::class, 'inbound_id', 'inbound_id');
    }

    public function actionItems()
    {
        return $this->hasMany(InboundActionItem::class, 'inbound_id', 'inbound_id');
    }

    /**
     * Auto-generate a reference code:
     * Format: EN / 2026 / 05 / IN / 001
     */
    public static function generateReferenceCode(InboundExternalEntity $entity): string
    {
        $prefix   = strtoupper(substr($entity->entity_code, 0, 2));
        $year     = now()->format('Y');
        $month    = now()->format('m');
        $lastCode = self::whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->count() + 1;
        $seq = str_pad($lastCode, 3, '0', STR_PAD_LEFT);
        return "{$prefix} / {$year} / {$month} / IN / {$seq}";
    }
}
