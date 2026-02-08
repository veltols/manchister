<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->user_id;

        $notifications = EmployeeNotification::where('employee_id', $employeeId)
            ->orderBy('notification_id', 'desc')
            ->paginate(20);

        return view('emp.notifications.index', compact('notifications'));
    }

    public function markRead(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->user_id;

        $ids = $request->input('ids');

        if ($ids) {
            EmployeeNotification::where('employee_id', $employeeId)
                ->whereIn('notification_id', (array)$ids)
                ->update(['is_seen' => 1]);
        } else {
            // Mark all as read if no IDs provided (optional based on UI requirement)
            EmployeeNotification::where('employee_id', $employeeId)
                ->where('is_seen', 0)
                ->update(['is_seen' => 1]);
        }

        return redirect()->back()->with('success', 'Notifications updated successfully.');
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->user_id;
        $perPage = $request->input('per_page', 20);

        $notifications = EmployeeNotification::where('employee_id', $employeeId)
            ->orderBy('notification_id', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'from' => $notifications->firstItem(),
                'to' => $notifications->lastItem(),
            ]
        ]);
    }
}
