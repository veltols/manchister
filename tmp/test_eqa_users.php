<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$users = DB::table('users_list')
    ->join('employees_list', 'users_list.user_id', '=', 'employees_list.employee_id')
    ->where('users_list.user_type', 'eqa')
    ->select('employees_list.employee_id', 'employees_list.first_name', 'employees_list.last_name')
    ->get();

echo "Count: " . $users->count() . "\n";
print_r($users->toArray());
