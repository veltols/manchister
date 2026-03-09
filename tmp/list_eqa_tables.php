<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = \Illuminate\Support\Facades\DB::select("SELECT table_name FROM information_schema.tables WHERE TABLE_SCHEMA = 'manchester' AND TABLE_NAME LIKE '%eqa%'");

foreach ($tables as $t) {
    echo $t->table_name . "\n";
}
