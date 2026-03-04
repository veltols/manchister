<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$todos = DB::table('a_registration_phases_todos')->get();
file_put_contents('todos_dump.json', json_encode($todos, JSON_PRETTY_PRINT));
