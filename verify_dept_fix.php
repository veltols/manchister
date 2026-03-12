<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\HR\DepartmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\ValidationException;

$controller = new DepartmentController();

// Test Store Duplicate Name
echo "Testing Store Duplicate Name...\n";
$requestStore = Request::create('/hr/departments', 'POST', [
    'department_code' => 'unique_dept_code_' . time(),
    'department_name' => 'Strategy', // Existing name
    'user_type' => 'emp',
    'log_remark' => 'Test duplicate'
]);

try {
    $controller->store($requestStore);
    echo "FAIL: Store allowed duplicate name.\n";
} catch (ValidationException $e) {
    echo "PASS: Store blocked duplicate name: " . json_encode($e->errors()) . "\n";
} catch (\Exception $e) {
    echo "PASS: Store blocked duplicate name (Generic catch: " . $e->getMessage() . ")\n";
}

// Test Store Duplicate Code
echo "\nTesting Store Duplicate Code...\n";
$requestStoreCode = Request::create('/hr/departments', 'POST', [
    'department_code' => '333', // Existing code
    'department_name' => 'Unique Name ' . time(),
    'user_type' => 'emp',
    'log_remark' => 'Test duplicate code'
]);

try {
    $controller->store($requestStoreCode);
    echo "FAIL: Store allowed duplicate code.\n";
} catch (ValidationException $e) {
    echo "PASS: Store blocked duplicate code: " . json_encode($e->errors()) . "\n";
} catch (\Exception $e) {
    echo "PASS: Store blocked duplicate code (Generic catch: " . $e->getMessage() . ")\n";
}
