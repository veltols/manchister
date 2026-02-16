<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = DB::select('SHOW TABLES');
$output = "";
foreach ($tables as $table) {
    $output .= array_values((array)$table)[0] . "\n";
}
file_put_contents('all_tables.txt', $output);
