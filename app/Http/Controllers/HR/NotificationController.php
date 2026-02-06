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
        $user = Auth::user();
        $employeeId = $user->user_id;

        $notifications = EmployeeNotification::where('employee_id', $employeeId)
            ->orderBy('notification_id', 'desc')
            ->paginate(50);

        return view('hr.notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->user_id;

        $id = $request->notification_id; // For single or 0
        $ids = $request->ids; // For multiple selection

        if ($ids) {
            EmployeeNotification::where('employee_id', $employeeId)
                ->whereIn('notification_id', (array)$ids)
                ->update(['is_seen' => 1]);
        } elseif ($id == 0) {
            // Mark all as read
            EmployeeNotification::where('employee_id', $employeeId)
                ->where('is_seen', 0)
                ->update(['is_seen' => 1]);
        } else {
            // Mark specific as read
            EmployeeNotification::where('employee_id', $employeeId)
                ->where('notification_id', $id)
                ->update(['is_seen' => 1]);
        }

        return response()->json(['success' => true]);
    }
}
