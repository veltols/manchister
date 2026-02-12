<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$tables = ['eqa_007_areas', 'eqa_007_interview'];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "Table $table EXISTS.\n";
        $columns = Schema::getColumnListing($table);
        echo "Columns: " . implode(', ', $columns) . "\n";
        $count = DB::table($table)->count();
        echo "Total records: $count\n\n";
    } else {
        echo "Table $table is MISSING!\n\n";
    }
}
