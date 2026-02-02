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
                Auth::login($user);

                if (in_array($user->user_type, ['hr', 'admin_hr'])) {
                    return redirect()->route('hr.dashboard');
                }

                if (in_array($user->user_type, ['root', 'sys_admin'])) {
                    return redirect()->route('admin.dashboard');
                }

                return redirect()->route('emp.dashboard'); # Default for emp
            }
        }

        // 2. Fallback: Check Training Provider (atps_list)
        // 2. Fallback: Check Training Provider (atps_list)
        // ATPs might login with email, but we use the 'username' input field
        $atp = \App\Models\TrainingProvider::where('atp_email', $username)->first();
        if ($atp) {
            // Check Password
            $pass = \App\Models\TrainingProviderPass::where('atp_id', $atp->atp_id)->first();
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
}
