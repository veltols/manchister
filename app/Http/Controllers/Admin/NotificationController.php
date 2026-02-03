<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $adminId = 550; // Hardcoded for now as per previous context, or use Auth::id() if auth is fully migrated
        // In a real scenario, use Auth::guard('admin')->id() or similar.
        // Assuming we are using the session 'user_id' or Auth user.
        
        // For now, let's try to use the logged in user's ID if available, otherwise fallback or error.
        // Based on previous chats, it seems we might need to rely on session or specific logic.
        // Let's assume standard Laravel Auth for now as per "admin login" discussions.
        
        $employeeId = Auth::id() ?? 550; // Defaulting to 550 (Admin) for dev testing if not logged in

        $notifications = EmployeeNotification::where('employee_id', $employeeId)
            ->orderBy('notification_id', 'desc')
            ->paginate(50);

        return view('admin.notifications.index', compact('notifications'));
    }
}
