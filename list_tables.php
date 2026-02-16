<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $name = array_values((array)$table)[0];
    if (strpos($name, 'quality') !== false || strpos($name, 'compliance') !== false) {
        echo $name . "\n";
    }
}
