<?php

namespace App\Services;

use App\Models\EmployeeNotification;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Send a notification to an employee, replicating legacy notifyUser logic.
     *
     * @param string $text The notification message
     * @param string $page The related page link (relative)
     * @param int $employeeId The ID of the employee to notify
     * @return EmployeeNotification
     */
    public static function send($text, $page, $employeeId)
    {
        $user = \App\Models\User::where('user_id', $employeeId)->first();
        
        if ($user && !str_starts_with($page, 'http')) {
            // Remove any existing prefix so we don't duplicate it (e.g. if they passed 'emp/leaves' or 'admin/leaves')
            $cleanPage = ltrim($page, '/');
            $cleanPage = preg_replace('#^(emp|admin|hr|eqa|root)/#', '', $cleanPage);

            // Determine the correct prefix for the user
            $prefix = match ($user->user_type) {
                'root', 'admin' => 'admin/',
                'hr'            => 'hr/',
                'eqa'           => 'eqa/',
                default         => 'emp/'
            };

            $page = $prefix . $cleanPage;
        }

        return EmployeeNotification::create([
            'notification_date' => Carbon::now(),
            'notification_text' => $text,
            'related_page' => $page,
            'employee_id' => $employeeId,
            'is_seen' => 0
        ]);
    }
}
