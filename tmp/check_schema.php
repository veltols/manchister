<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$cols = DB::select('SHOW COLUMNS FROM atps_list');
foreach ($cols as $col)
    print_r($col);
