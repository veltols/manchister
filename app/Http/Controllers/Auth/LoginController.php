<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'idder' => 'required', // Assuming 'idder' is the input name from the original form
            'passer' => 'required',
        ]);

        // Map inputs to database columns
        // 'user_email' is the column in users_list
        $authCredentials = [
            'user_email' => $credentials['idder'],
            'password' => $credentials['passer']
        ];

        if (Auth::attempt($authCredentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
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
