<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Designation;
use Illuminate\Support\Facades\DB;

$all = Designation::all();
echo "\nAll Designations:\n";
foreach ($all as $d) {
    echo "- #{$d->designation_id}: {$d->designation_code} | {$d->designation_name} | Dept ID: {$d->department_id}\n";
}
