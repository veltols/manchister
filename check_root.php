<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$user_email = 'root';
$user = DB::table('users_list')->where('user_email', $user_email)->first();
if ($user) {
    $p = DB::table('employees_list_pass')->where('employee_id', $user->user_id)->where('is_active', 1)->first();
    if ($p && Hash::check('123456', $p->pass_value)) {
        echo "Root password is 123456\n";
    } else {
        echo "Root password is NOT 123456 (Hash: " . ($p->pass_value ?? 'NONE') . ")\n";
    }
}
