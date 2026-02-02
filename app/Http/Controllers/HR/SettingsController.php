<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function profile()
    {
        // Legacy sys_lists_themes
        $themes = DB::table('users_list_themes')->orderBy('user_theme_id')->get();
        $user = Auth::user(); // Assuming user links to legacy fields user_theme_id etc via some mechanism or we update user table

        // If user table doesn't have theme_id, we might need a separate settings table or add column
        // For now, assuming user model has attributes or we pass current settings.

        return view('hr.settings.profile', compact('themes', 'user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'user_theme_id' => 'required|integer',
            // 'user_lang' => 'required|string|in:en,ar'
        ]);

        $user = Auth::user();
        if ($user) {
            $user->user_theme_id = $request->user_theme_id;
            // $user->user_lang = $request->user_lang; // If column exists
            $user->save();
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
