<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->username;
        $password = $request->password;

        // Custom Auth Logic to match legacy systems (get_auth_user)
        Log::info("Attempting login for: $username");

        // 1. Check LegacyUser (Employees / HR)
        // Note: Legacy system uses 'user_email' column for username
        $user = User::where('user_email', $username)->first();

        if ($user) {
            // ... [Existing Employee Password Check Logic] ...
            $isValid = false;
            if (in_array($user->user_type, ['emp', 'hr', 'root', 'sys_admin', 'admin_hr'])) {
                $employee = $user->employee;
                if ($employee && $employee->passwordData && Hash::check($password, $employee->passwordData->pass_value)) {
                    $isValid = true;
                }
            }

            if ($isValid) {
                // Password is correct - now send OTP for 2FA instead of logging in
                Log::info('Password verified for: ' . $username . ', sending OTP for 2FA');

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
                $otp->update(['otp_code' => Hash::make($plainOtp)]);

                // Store pending 2FA info in session
                $request->session()->put([
                    'pending_2fa' => true,
                    'pending_user_id' => $user->record_id,
                    'pending_email' => $user->user_email,
                    'pending_user_type' => $user->user_type,
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
                    $request->session()->forget(['pending_2fa', 'pending_user_id', 'pending_email', 'pending_user_type', '2fa_timestamp']);
                    
                    return back()->withErrors([
                        'username' => 'Failed to send OTP. Please try again later.'
                    ])->onlyInput('username');
                }
            }
        }

        // 2. Fallback: Check Training Provider (atps_list)
        // ATPs might login with email, but we use the 'username' input field
        $atp = \App\Models\TrainingProvider::where('atp_email', $username)->first();
        if ($atp) {
            // Check Password
            $pass = \App\Models\TrainingProviderPass::where('atp_id', $atp->atp_id)
                ->where('is_active', '1')
                ->latest('pass_id')
                ->first();
            if ($pass && Hash::check($password, $pass->pass_value)) {
                // Log them in via Session for the Portal
                session([
                    'atp_id' => $atp->atp_id,
                    'atp_name' => $atp->atp_name_en,
                    'user_type' => 'atp'
                ]);

                // Redirect based on status (Registration vs Dashboard)
                if ($atp->status_id <= 2) {
                    return redirect()->route('rc.portal.wizard.step1');
                } else {
                    return redirect()->route('rc.portal.dashboard');
                }
            }
        }

        Log::warning("Login failed for: $username - Invalid credentials.");
        return back()->withErrors(['username' => 'Invalid credentials.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ]);

        $user = Auth::user();

        // Custom password logic for Employees/HR/Admins
        if ($user && in_array($user->user_type, ['emp', 'hr', 'root', 'sys_admin', 'admin_hr'])) {
            $employee = $user->employee;
            if ($employee && $employee->passwordData && Hash::check($request->current_password, $employee->passwordData->pass_value)) {
                
                // 1. Deactivate all old passwords for this employee
                \App\Models\EmployeesListPass::where('employee_id', $employee->employee_id)->update(['is_active' => '0']);

                // 2. Insert new password record (Historical Log)
                $newPass = new \App\Models\EmployeesListPass();
                $newPass->employee_id = $employee->employee_id;
                $newPass->pass_value = Hash::make($request->new_password);
                $newPass->is_active = '1';
                $newPass->save();

                // 3. Mark employee as having a password set
                $employee->is_pass = '1';
                $employee->save();
                
                return back()->with('success', 'Password updated successfully.');
            }
        }

        return back()->withErrors(['current_password' => 'The current password you provided is incorrect.']);
    }

    /**
     * Show OTP verification form
     */
    public function showOtpVerifyForm(Request $request)
    {
        // Check if there's a pending 2FA session
        if (!$request->session()->has('pending_2fa')) {
            return redirect()->route('login')->withErrors([
                'username' => 'Session expired. Please login again.'
            ]);
        }

        $email = $request->session()->get('pending_email') ?? session('email');
        
        return view('auth.otp-verify', compact('email'));
    }

    /**
     * Verify OTP and complete login
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
                    'otp' => 'OTP has expired. Please login again.'
                ])->withInput();
            }

            if ($otp->attempts >= 5) {
                return back()->withErrors([
                    'otp' => 'Too many failed attempts. Please login again.'
                ])->withInput();
            }

            return back()->withErrors([
                'otp' => 'Invalid OTP. Please try again.'
            ])->withInput();
        }

        // OTP verified successfully - now check for pending 2FA session
        if (!$request->session()->has('pending_2fa')) {
            return back()->withErrors([
                'otp' => 'Session expired. Please login again.'
            ])->withInput();
        }

        // Verify session matches
        $pendingEmail = $request->session()->get('pending_email');
        $pendingUserId = $request->session()->get('pending_user_id');
        $pendingUserType = $request->session()->get('pending_user_type');
        $timestamp = $request->session()->get('2fa_timestamp');
        
        // Check if session has expired (5 minutes timeout)
        if ($timestamp && (now()->timestamp - $timestamp) > 300) {
            // Clear session data
            $request->session()->forget(['pending_2fa', 'pending_user_id', 'pending_email', 'pending_user_type', '2fa_timestamp']);
            
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
        
        $user = User::where('record_id', $pendingUserId)->first();

        if (!$user) {
            return back()->withErrors([
                'otp' => 'User not found.'
            ])->withInput();
        }

        // Complete the login
        Auth::login($user);
        $request->session()->regenerate();
        
        // Clear 2FA session data
        $request->session()->forget(['pending_2fa', 'pending_user_id', 'pending_email', 'pending_user_type', '2fa_timestamp']);

        Log::info('2FA login successful for: ' . $email);

        // Redirect based on user type
        if (in_array($user->user_type, ['hr', 'admin_hr'])) {
            return redirect()->route('hr.dashboard');
        }

        if (in_array($user->user_type, ['root', 'sys_admin'])) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('emp.dashboard');
    }
}
