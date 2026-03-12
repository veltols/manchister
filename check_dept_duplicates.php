<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$duplicates = DB::table('employees_list_departments')
    ->select('department_name', DB::raw('COUNT(*) as count'))
    ->groupBy('department_name')
    ->having('count', '>', 1)
    ->get();

echo "Duplicate Department Names:\n";
foreach ($duplicates as $duplicate) {
    echo "- {$duplicate->department_name} ({$duplicate->count})\n";
}

$deptCodes = DB::table('employees_list_departments')
    ->select('department_code', DB::raw('COUNT(*) as count'))
    ->groupBy('department_code')
    ->having('count', '>', 1)
    ->get();

echo "\nDuplicate Department Codes:\n";
foreach ($deptCodes as $code) {
    echo "- {$code->department_code} ({$code->count})\n";
}
