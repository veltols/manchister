<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $notifications = Notification::where('employee_id', $employeeId)
            ->orderBy('notification_id', 'desc')
            ->paginate(20);

        return view('emp.notifications.index', compact('notifications'));
    }

    public function markRead(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee ? $user->employee->employee_id : 0;

        $ids = $request->input('ids');

        if ($ids) {
            Notification::where('employee_id', $employeeId)
                ->whereIn('notification_id', (array)$ids)
                ->update(['is_seen' => 1]);
        } else {
            // Mark all as read if no IDs provided (optional based on UI requirement)
            Notification::where('employee_id', $employeeId)
                ->where('is_seen', 0)
                ->update(['is_seen' => 1]);
        }

        return redirect()->back()->with('success', 'Notifications updated successfully.');
    }
}
