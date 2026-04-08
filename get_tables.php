<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$models = [
    'App\Models\SupportTicketCategory', 
    'App\Models\LeaveType', 
    'App\Models\Priority', 
    'App\Models\IncidentType', 
    'App\Models\AssetCategory', 
    'App\Models\SupportServiceCategory', 
    'App\Models\CommunicationType', 
    'App\Models\UsersListTheme'
];

foreach ($models as $m) {
    if (class_exists($m)) {
        echo $m . ": " . (new $m)->getTable() . PHP_EOL;
    }
}
