<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$output = "ATP_COMPLIANCE:\n";
$columns = DB::select('DESCRIBE atp_compliance');
foreach ($columns as $column) {
    $output .= $column->Field . " (" . $column->Type . ")\n";
}

$output .= "\nQUALITY_STANDARD_MAIN:\n";
$columns2 = DB::select('DESCRIBE quality_standard_main');
foreach ($columns2 as $column) {
    $output .= $column->Field . " (" . $column->Type . ")\n";
}

file_put_contents('table_structure.txt', $output);
