<?php
use Illuminate\Support\Facades\DB;

$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $tableName = array_values((array) $table)[0];
    if (strpos($tableName, 'leave') !== false) {
        echo "Found Table: " . $tableName . "\n";
    }
}
