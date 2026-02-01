<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->employee_id ?? Auth::id();
        $notifications = Notification::where('employee_id', $userId)
                                     ->orderBy('notification_id', 'desc')
                                     ->paginate(15);
                                     
        // Mark all as read when visiting page? Or via API
        // For now, let's keep it simple.
        
        return view('notifications.index', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
         $userId = Auth::user()->employee_id ?? Auth::id();
         $notif = Notification::where('notification_id', $id)->where('employee_id', $userId)->first();
         if($notif){
             $notif->is_seen = 1;
             $notif->save();
         }
         return response()->json(['success' => true]);
    }
}
