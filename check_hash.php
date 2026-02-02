<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$user_id = 42; // ID for 'eqa'
$p = DB::table('employees_list_pass')->where('employee_id', $user_id)->orderBy('pass_id', 'desc')->first();
if ($p) {
    echo "HASH: " . $p->pass_value . "\n";
    if (Hash::check('123456', $p->pass_value)) {
        echo "Password '123456' is CORRECT for this hash.\n";
    } else {
        echo "Password '123456' is WRONG for this hash.\n";
    }
} else {
    echo "NO PASSWORD FOUND\n";
}
