<?php

define('LARAVEL_START', microtime(true));
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function getTable($t)
{
    if (Schema::hasTable($t)) {
        return Schema::getColumnListing($t);
    }
    return "MISSING";
}

$data = [
    'atps_list' => getTable('atps_list'),
    'atps_form_init' => getTable('atps_form_init'),
    'atps_list_qualifications' => getTable('atps_list_qualifications'),
    'atps_list_faculties' => getTable('atps_list_faculties'),
    'atps_list_faculties_types' => getTable('atps_list_faculties_types'),
    'atps_learners_statistics' => getTable('atps_learners_statistics'),
    'atps_electronic_systems' => getTable('atps_electronic_systems'),
    'atps_list_categories' => getTable('atps_list_categories'),
    'atps_list_industries' => getTable('atps_list_industries'),
];

file_put_contents('schema_dump.json', json_encode($data, JSON_PRETTY_PRINT));
echo "Done.";
