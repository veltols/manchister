<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        Log::info('Login attempt for: ' . $request->username);

        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Map inputs to database columns
        $authCredentials = [
            'user_email' => $credentials['username'],
            'password' => $credentials['password']
        ];

        // Verify password (but don't log in yet - need OTP first)
        if (Auth::validate($authCredentials)) {
            // Password is correct - now send OTP for 2FA
            $user = \App\Models\User::where('user_email', $credentials['username'])->first();
            
            if (!$user) {
                return back()->withErrors([
                    'username' => 'User not found.',
                ])->onlyInput('username');
            }

            Log::info('Password verified for: ' . $credentials['username'] . ', sending OTP for 2FA');

            // Check rate limiting
            if (!\App\Models\LoginOtp::canRequestOtp($user->user_email)) {
                $waitTime = \App\Models\LoginOtp::getRateLimitWaitTime($user->user_email);
                $minutes = ceil($waitTime / 60);
                
                return back()->withErrors([
                    'username' => "Too many login attempts. Please try again in {$minutes} minute(s)."
                ])->onlyInput('username');
            }

            // Generate OTP
            $otp = \App\Models\LoginOtp::generateOtp(
                $user->user_email,
                $request->ip(),
                $request->userAgent()
            );

            // Generate plain OTP for email
            $plainOtp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Update with hashed version
            $otp->update(['otp_code' => \Illuminate\Support\Facades\Hash::make($plainOtp)]);

            // Store pending 2FA info in session
            $request->session()->put([
                'pending_2fa' => true,
                'pending_user_id' => $user->record_id,
                'pending_email' => $user->user_email,
                '2fa_timestamp' => now()->timestamp
            ]);

            // Send OTP email
            try {
                $user->notify(new \App\Notifications\LoginOtpNotification($plainOtp));
                
                Log::info('2FA OTP sent to: ' . $user->user_email);
                
                return redirect()->route('login.otp.verify')
                    ->with('email', $user->user_email)
                    ->with('success', 'Password verified! Please check your email for the OTP code.');
            } catch (\Exception $e) {
                Log::error('Failed to send 2FA OTP email: ' . $e->getMessage());
                
                // Clear session data
                $request->session()->forget(['pending_2fa', 'pending_user_id', 'pending_email', '2fa_timestamp']);
                
                return back()->withErrors([
                    'username' => 'Failed to send OTP. Please try again later.'
                ])->onlyInput('username');
            }
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Show OTP request form
     */
    public function showOtpRequestForm()
    {
        return view('auth.otp-request');
    }

    /**
     * Send OTP to email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        // Check rate limiting
        if (!\App\Models\LoginOtp::canRequestOtp($email)) {
            $waitTime = \App\Models\LoginOtp::getRateLimitWaitTime($email);
            $minutes = ceil($waitTime / 60);
            
            return back()->withErrors([
                'email' => "Too many OTP requests. Please try again in {$minutes} minute(s)."
            ])->withInput();
        }

        // Check if user exists (don't reveal if email doesn't exist for security)
        $user = \App\Models\User::where('user_email', $email)->first();
        
        if (!$user) {
            // For security, don't reveal that email doesn't exist
            // Show success message anyway
            return redirect()->route('login.otp.verify')
                ->with('email', $email)
                ->with('success', 'If this email exists in our system, an OTP has been sent.');
        }

        // Generate OTP
        $otp = \App\Models\LoginOtp::generateOtp(
            $email,
            $request->ip(),
            $request->userAgent()
        );

        // Generate plain OTP for email
        $plainOtp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Update with hashed version
        $otp->update(['otp_code' => \Illuminate\Support\Facades\Hash::make($plainOtp)]);

        // Send email notification
        try {
            $user->notify(new \App\Notifications\LoginOtpNotification($plainOtp));
            
            Log::info('OTP sent to: ' . $email);
            
            return redirect()->route('login.otp.verify')
                ->with('email', $email)
                ->with('success', 'OTP has been sent to your email.');
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());
            
            return back()->withErrors([
                'email' => 'Failed to send OTP. Please try again later.'
            ])->withInput();
        }
    }

    /**
     * Show OTP verification form
     */
    public function showOtpVerifyForm(Request $request)
    {
        $email = $request->session()->get('email') ?? $request->query('email');
        
        if (!$email) {
            return redirect()->route('login.otp')->withErrors([
                'email' => 'Please request an OTP first.'
            ]);
        }

        return view('auth.otp-verify', ['email' => $email]);
    }

    /**
     * Verify OTP and login
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $email = $request->email;
        $otpCode = $request->otp;

        // Find the latest unverified OTP for this email
        $otp = \App\Models\LoginOtp::where('email', $email)
            ->whereNull('verified_at')
            ->latest('created_at')
            ->first();

        if (!$otp) {
            return back()->withErrors([
                'otp' => 'No valid OTP found. Please request a new one.'
            ])->withInput();
        }

        // Verify OTP
        if (!$otp->verify($otpCode)) {
            if ($otp->isExpired()) {
                return back()->withErrors([
                    'otp' => 'OTP has expired. Please request a new one.'
                ])->withInput();
            }

            if ($otp->attempts >= 5) {
                return back()->withErrors([
                    'otp' => 'Too many failed attempts. Please request a new OTP.'
                ])->withInput();
            }

            return back()->withErrors([
                'otp' => 'Invalid OTP. Please try again.'
            ])->withInput();
        }

        // OTP verified successfully - now check for pending 2FA session
        
        // Check if this is part of a 2FA login flow
        if (!$request->session()->has('pending_2fa')) {
            // This might be a standalone OTP login (old flow)
            // Still allow it for backward compatibility
            $user = \App\Models\User::where('user_email', $email)->first();
        } else {
            // This is a 2FA login - verify session matches
            $pendingEmail = $request->session()->get('pending_email');
            $pendingUserId = $request->session()->get('pending_user_id');
            $timestamp = $request->session()->get('2fa_timestamp');
            
            // Check if session has expired (5 minutes timeout)
            if ($timestamp && (now()->timestamp - $timestamp) > 300) {
                // Clear session data
                $request->session()->forget(['pending_2fa', 'pending_user_id', 'pending_email', '2fa_timestamp']);
                
                return back()->withErrors([
                    'otp' => 'Session expired. Please login again.'
                ])->withInput();
            }
            
            // Verify email matches
            if ($pendingEmail !== $email) {
                return back()->withErrors([
                    'otp' => 'Email mismatch. Please login again.'
                ])->withInput();
            }
            
            $user = \App\Models\User::where('record_id', $pendingUserId)->first();
        }

        if (!$user) {
            return back()->withErrors([
                'otp' => 'User not found.'
            ])->withInput();
        }

        // Complete the login
        Auth::login($user);
        $request->session()->regenerate();
        
        // Clear 2FA session data
        $request->session()->forget(['pending_2fa', 'pending_user_id', 'pending_email', '2fa_timestamp']);

        Log::info('2FA login successful for: ' . $email);

        // Redirect based on user type
        switch ($user->user_type) {
            case 'root':
            case 'sys_admin':
                return redirect()->route('admin.dashboard');
            case 'hr':
            case 'admin_hr':
                return redirect()->route('hr.dashboard');
            case 'emp':
                return redirect()->route('emp.dashboard');
            case 'eqa':
                return redirect()->route('emp.dashboard');
            default:
                return redirect()->route('emp.dashboard');
        }
    }
}

