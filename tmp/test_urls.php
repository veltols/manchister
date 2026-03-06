<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "APP_URL: " . config('app.url') . "\n";
echo "Route 'emp.atps.eqa-users': " . route('emp.atps.eqa-users') . "\n";
echo "URL '/eqa/atps': " . url('/eqa/atps') . "\n";
echo "Relative Route 'emp.atps.eqa-users': " . route('emp.atps.eqa-users', [], false) . "\n";
