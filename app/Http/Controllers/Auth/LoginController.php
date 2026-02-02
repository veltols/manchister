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
        // 'user_email' is the column in users_list
        $authCredentials = [
            'user_email' => $credentials['username'],
            'password' => $credentials['password']
        ];

        if (Auth::attempt($authCredentials)) {
            Log::info('Login successful for: ' . $credentials['username']);
            $request->session()->regenerate();

            $user = Auth::user();

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
                    // Redirect EQA to their main workspace (e.g., Training Providers List or Dashboard if exists)
                    // Since EQA sidebar prioritizes "Training Providers" or "Dashboard", let's default to dashboard if routed or index
                    // If no explicit eqa dashboard exists in routes relative to 'eqa.dashboard', we check.
                    // Assuming eqa.atps.index is the main work hub if no dashboard.
                    // But typically there is a dashboard. 
                    // Let's assume we want a generic redirect or route checking.
                    // For now, EQA goes to eqa.atps.index as a safe bet if no dashboard, OR create a dashboard route.
                    // Legacy had `DIR_dashboard`.
                    // We haven't created `eqa.dashboard` yet. I will redirect to `eqa.atps.index` for now or create the route.
                    return redirect()->route('eqa.atps.index');
                default:
                    return redirect()->route('emp.dashboard'); // Fallback
            }
        }

        return back()->withErrors([
            'idder' => 'The provided credentials do not match our records.',
        ])->onlyInput('idder');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
