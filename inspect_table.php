<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = Illuminate\Support\Facades\Schema::getColumnListing('users_list_themes');
print_r($columns);

$first = Illuminate\Support\Facades\DB::table('users_list_themes')->first();
print_r($first);
