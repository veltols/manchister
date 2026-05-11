<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$table = 'm_communications_list';
$status = DB::select("SHOW TABLE STATUS LIKE '$table'");
if ($status) {
    echo "Engine: " . $status[0]->Engine . "\n";
}

$columns = DB::select("SHOW COLUMNS FROM $table WHERE Field = 'communication_id'");
if ($columns) {
    echo "Type: " . $columns[0]->Type . "\n";
}
