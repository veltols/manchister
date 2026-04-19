<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;
use App\Models\EmployeeNotification;
use App\Models\User;
use Carbon\Carbon;

class CheckAssetAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:check-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for assets that are nearing expiry and send alerts to admins.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Find assets that have an expiry_date and alert_days set.
        $assets = Asset::whereNotNull('expiry_date')
            ->whereNotNull('alert_days')
            ->get();

        $admins = User::whereIn('user_type', ['sys_admin', 'root', 'admin_hr'])->get();

        $notificationsSent = 0;

        foreach ($assets as $asset) {
            $expiryDate = Carbon::parse($asset->expiry_date)->startOfDay();
            $alertDays = (int) $asset->alert_days;
            $today = Carbon::now()->startOfDay();

            // Calculate the date when the alert should start showing
            $alertDate = $expiryDate->copy()->subDays($alertDays);

            // We want to alert if today is between the alertDate and expiryDate,
            // but ONLY if we haven't already sent a notification for this asset in this specific alert window.
            if ($today->between($alertDate, $expiryDate)) {
                
                $message = "Asset Alert: {$asset->asset_name} ({$asset->asset_ref}) is expiring on {$asset->expiry_date->format('M d, Y')}. " . ($asset->alert_description ? "Note: {$asset->alert_description}" : "");

                foreach ($admins as $admin) {
                    $targetId = $admin->employee_id ?? $admin->user_id;

                    // Prevent duplicate notifications in this expiry window
                    $exists = EmployeeNotification::where('employee_id', $targetId)
                        ->where('notification_text', $message)
                        ->whereDate('notification_date', '>=', $alertDate)
                        ->exists();

                    if (!$exists) {
                        EmployeeNotification::create([
                            'employee_id' => $targetId,
                            'notification_date' => now(),
                            'notification_text' => $message,
                            'related_page' => route('admin.assets.show', $asset->asset_id),
                            'is_seen' => 0
                        ]);
                        $notificationsSent++;
                    }
                }
            }
        }

        $this->info("Asset alerts checked. Sent {$notificationsSent} notifications.");

        return 0;
    }
}
