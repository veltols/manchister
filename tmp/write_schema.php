<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = \Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE atps_eqa_details");
file_put_contents('tmp/schema.txt', $tables[0]->{'Create Table'} . "\n");
