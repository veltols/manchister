<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProbationReview extends Model
{
    protected $table      = 'hr_probation_reviews';
    protected $primaryKey = 'review_id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $guarded = [];

    // Status constants
    const STATUS_PENDING_MANAGER = 'pending_manager';
    const STATUS_REVIEWED        = 'reviewed';
    const STATUS_APPROVED        = 'approved';
    const STATUS_REJECTED        = 'rejected';

    // -----------------------------------------------------------------------
    // Relationships
    // -----------------------------------------------------------------------

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function lineManager()
    {
        return $this->belongsTo(Employee::class, 'line_manager_id', 'employee_id');
    }

    public function gm()
    {
        return $this->belongsTo(User::class, 'gm_id', 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING_MANAGER => 'Pending Manager Review',
            self::STATUS_REVIEWED        => 'Forwarded to GM',
            self::STATUS_APPROVED        => 'Approved by GM',
            self::STATUS_REJECTED        => 'Rejected by GM',
            default                      => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING_MANAGER => 'from-amber-400 to-amber-600',
            self::STATUS_REVIEWED        => 'from-blue-500 to-indigo-600',
            self::STATUS_APPROVED        => 'from-emerald-500 to-green-600',
            self::STATUS_REJECTED        => 'from-rose-500 to-red-600',
            default                      => 'from-slate-400 to-slate-600',
        };
    }
}
