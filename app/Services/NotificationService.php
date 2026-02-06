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
        return EmployeeNotification::create([
            'notification_date' => Carbon::now(),
            'notification_text' => $text,
            'related_page' => $page,
            'employee_id' => $employeeId,
            'is_seen' => 0
        ]);
    }
}
