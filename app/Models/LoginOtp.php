<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LoginOtp extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'email',
        'otp_code',
        'expires_at',
        'verified_at',
        'ip_address',
        'user_agent',
        'attempts',
        'created_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Generate a new OTP for the given email
     */
    public static function generateOtp(string $email, string $ipAddress = null, string $userAgent = null): self
    {
        // Delete any existing unverified OTPs for this email
        self::where('email', $email)
            ->whereNull('verified_at')
            ->delete();

        // Generate 6-digit OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return self::create([
            'email' => $email,
            'otp_code' => Hash::make($otpCode),
            'expires_at' => Carbon::now()->addMinutes(10),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'created_at' => Carbon::now(),
        ]);
    }

    /**
     * Verify if the OTP is valid
     */
    public function verify(string $otpCode): bool
    {
        // Check if already verified
        if ($this->verified_at) {
            return false;
        }

        // Check if expired
        if ($this->isExpired()) {
            return false;
        }

        // Check if too many attempts
        if ($this->attempts >= 5) {
            return false;
        }

        // Increment attempts
        $this->increment('attempts');

        // Verify OTP
        if (Hash::check($otpCode, $this->otp_code)) {
            $this->update(['verified_at' => Carbon::now()]);
            return true;
        }

        return false;
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return Carbon::now()->isAfter($this->expires_at);
    }

    /**
     * Get the plain OTP code (only available immediately after generation)
     */
    public function getPlainOtp(): string
    {
        // This will be set temporarily after generation
        return $this->plain_otp ?? '';
    }

    /**
     * Clean up old OTPs (older than 24 hours)
     */
    public static function cleanup(): void
    {
        self::where('created_at', '<', Carbon::now()->subHours(24))->delete();
    }

    /**
     * Check rate limiting for email
     */
    public static function canRequestOtp(string $email): bool
    {
        $recentOtps = self::where('email', $email)
            ->where('created_at', '>', Carbon::now()->subMinutes(15))
            ->count();

        return $recentOtps < 3;
    }

    /**
     * Get remaining time until next OTP request allowed
     */
    public static function getRateLimitWaitTime(string $email): ?int
    {
        $oldestRecent = self::where('email', $email)
            ->where('created_at', '>', Carbon::now()->subMinutes(15))
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$oldestRecent) {
            return null;
        }

        $waitUntil = $oldestRecent->created_at->addMinutes(15);
        $secondsRemaining = Carbon::now()->diffInSeconds($waitUntil, false);

        return max(0, $secondsRemaining);
    }
}
