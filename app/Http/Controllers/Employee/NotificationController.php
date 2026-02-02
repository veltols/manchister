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

        // Mark all as seen
        Notification::where('employee_id', $employeeId)
            ->where('is_seen', 0)
            ->update(['is_seen' => 1]);

        return view('emp.notifications.index', compact('notifications'));
    }
}
