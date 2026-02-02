<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$users = DB::table('users_list')->get();
foreach ($users as $u) {
    echo "USER_EMAIL: " . $u->user_email . " | USER_ID: " . $u->user_id . "\n";
    $p = DB::table('employees_list_pass')->where('employee_id', $u->user_id)->where('is_active', 1)->first();
    if ($p) {
        echo "  PASSWORD: FOUND\n";
    } else {
        echo "  PASSWORD: MISSING\n";
    }
}
