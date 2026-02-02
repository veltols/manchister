<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$columns = DB::select('SHOW COLUMNS FROM employees_list');
foreach ($columns as $column) {
    if ($column->Null === 'NO' && $column->Default === null && $column->Extra !== 'auto_increment') {
        echo "REQUIRED: " . $column->Field . " (" . $column->Type . ")\n";
    }
}
echo "Check complete.\n";
