<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserTheme;

use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $themes = UserTheme::paginate(12);
        return view('emp.settings.index', compact('user', 'themes'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'user_theme_id' => 'required|exists:users_list_themes,user_theme_id',
        ]);

        $user = Auth::user();
        if ($user) { // Only update if user is authenticated
             // Update the model instance
            Log::info('Updating theme for user: ' . $user->user_id . ' to theme: ' . $request->user_theme_id);
            $user->user_theme_id = $request->user_theme_id;
            // Explicitly call update on the query builder to bypass Laravel's mass assignment if needed, 
            // though $fillable was updated. Using save() is cleaner.
            $saved = $user->save(); 
            Log::info('Theme update result: ' . ($saved ? 'Success' : 'Failed'));
        }

        return redirect()->back()->with('success', 'Theme updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'new_status' => 'required|exists:employees_list_staus,staus_id',
        ]);

        $user = Auth::user();
        if ($user && $user->employee) {
            $user->employee->emp_status_id = $request->new_status;
            $user->employee->save();
            return response()->json([['success' => true, 'message' => 'Status updated']]);
        }

        return response()->json([['success' => false, 'message' => 'User not found']], 404);
    }
}
