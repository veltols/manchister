<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = \Illuminate\Support\Facades\DB::select("SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.columns WHERE TABLE_SCHEMA = 'manchester' AND COLUMN_NAME IN ('assigned_to', 'eqa_user_id', 'eqa_id', 'user_id', 'employee_id')");

foreach ($tables as $t) {
    echo $t->TABLE_NAME . " -> " . $t->COLUMN_NAME . "\n";
}
