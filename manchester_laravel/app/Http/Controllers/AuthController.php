<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LegacyUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        // Custom Auth Logic to match legacy systems (get_auth_user)
        $user = LegacyUser::where('user_email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        // Verify Password based on User Type
        $isValid = false;
        
        if ($user->user_type == 'emp' || $user->user_type == 'hr' || $user->user_type == 'root') {
            // Employee Check
            $employee = $user->employee;
            if ($employee && $employee->passwordData) {
                 // Using Hash::check because legacy uses BCrypt
                if (Hash::check($password, $employee->passwordData->pass_value)) {
                    $isValid = true;
                }
            }
        } elseif ($user->user_type == 'atp') {
            // ATP Check
            $atp = $user->atp;
            if ($atp && $atp->passwordData) {
                if (Hash::check($password, $atp->passwordData->pass_value)) {
                    $isValid = true;
                }
            }
        }

        if ($isValid) {
            Auth::login($user);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
