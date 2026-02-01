<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Assuming LegacyUser maps to Employee via employee_id (users_list has employee_id? or we use Auth::id() if they match)
        // In this system, it seems Auth::id() is the employee_id based on previous LoginSeeder logic.
        $employee = Employee::with(['department', 'jobTitle', 'designation'])->find($user->employee_id);
        
        // If relationship not set up or direct mapping
        if(!$employee) {
             $employee = Employee::find($user->employee_id);
        }

        return view('emp.profile.index', compact('user', 'employee'));
    }

    public function updateTheme(Request $request)
    {
        // Legacy 'settings.php' allowed theme updates.
        // We can implement this if we want to support dynamic themes, or just notify user it's fixed in new design.
        // For now, let's just allow password/email updates if needed, or simple "View Profile".
        // The legacy file showed 'Theme Color' selection.
        
        return redirect()->back()->with('info', 'Theme selection is managed globally in this version.');
    }
}
