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
        $user = Auth::user();
        $employeeId = $user->user_id;

        $notifications = EmployeeNotification::where('employee_id', $employeeId)
            ->orderBy('notification_id', 'desc')
            ->paginate(50);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->user_id;
        
        $id = $request->notification_id; // For single or 0
        $ids = $request->ids; // For multiple selection (as comma separated string from view)

        if ($ids) {
            $idArray = is_array($ids) ? $ids : explode(',', $ids);
            EmployeeNotification::where('employee_id', $employeeId)
                ->whereIn('notification_id', $idArray)
                ->update(['is_seen' => 1]);
        } elseif ($id === '0' || $id === 0) {
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

        return redirect()->back()->with('success', 'Notifications updated.');
    }
}
