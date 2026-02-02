<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$user_email = 'eqa';
$user = DB::table('users_list')->where('user_email', $user_email)->first();

if ($user) {
    $employee_id = $user->user_id;
    $new_hash = Hash::make('123456');

    // Update or Insert password
    DB::table('employees_list_pass')
        ->where('employee_id', $employee_id)
        ->update(['is_active' => 0]);

    DB::table('employees_list_pass')->insert([
        'employee_id' => $employee_id,
        'pass_value' => $new_hash,
        'is_active' => 1
    ]);

    echo "Password for 'eqa' reset to '123456'.\n";
} else {
    echo "User 'eqa' not found.\n";
}
