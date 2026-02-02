<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$columns = DB::select('SHOW COLUMNS FROM employees_list');
foreach ($columns as $column) {
    echo "COLUMN: " . $column->Field . " | Type: " . $column->Type . " | Null: " . $column->Null . " | Default: " . ($column->Default === null ? 'NULL' : $column->Default) . "\n";
}
