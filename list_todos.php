<?php

define('LARAVEL_START', microtime(true));
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
File::put('C:\xampp\htdocs\manchister\todo_check_paths.json', DB::table('a_registration_phases_todos')->get()->toJson(JSON_PRETTY_PRINT));
echo "Done.";
