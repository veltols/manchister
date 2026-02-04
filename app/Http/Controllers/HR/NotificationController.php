<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $employeeId = Auth::id() ?? 550; // Fallback to 550 for testing if not authenticated

        $notifications = EmployeeNotification::where('employee_id', $employeeId)
            ->orderBy('notification_id', 'desc')
            ->paginate(50);

        return view('hr.notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $employeeId = Auth::id() ?? 550;
        $notificationId = (int) $request->notification_id;

        if ($notificationId === 0) {
            // Mark all as read
            EmployeeNotification::where('employee_id', $employeeId)
                ->where('is_seen', 0)
                ->update(['is_seen' => 1]);
        } else {
            // Mark specific as read
            EmployeeNotification::where('employee_id', $employeeId)
                ->where('notification_id', $notificationId)
                ->update(['is_seen' => 1]);
        }

        return response()->json(['success' => true]);
    }
}
