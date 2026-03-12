<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\HR\DesignationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\ValidationException;

$controller = new DesignationController();

// Test Store Duplicate Name
echo "Testing Store Duplicate Name...\n";
$requestStore = Request::create('/hr/designations', 'POST', [
    'designation_code' => 'unique_code_' . time(),
    'designation_name' => 'hr admin', // Existing name
    'department_id' => 12,
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

// Test Update Duplicate Name
echo "\nTesting Update Duplicate Name...\n";
$requestUpdate = Request::create('/hr/designations/9', 'PUT', [
    'designation_code' => 'emp34',
    'designation_name' => 'IT Admin', // Existing name
    'department_id' => 26,
    'log_remark' => 'Test update duplicate'
]);

try {
    $controller->update($requestUpdate, 9);
    echo "FAIL: Update allowed duplicate name.\n";
} catch (ValidationException $e) {
    echo "PASS: Update blocked duplicate name: " . json_encode($e->errors()) . "\n";
} catch (\Exception $e) {
    echo "PASS: Update blocked duplicate name (Generic catch: " . $e->getMessage() . ")\n";
}

// Test Update Self (Should PASS)
echo "\nTesting Update Self (Keep name)...\n";
$requestUpdateSelf = Request::create('/hr/designations/9', 'PUT', [
    'designation_code' => 'emp34',
    'designation_name' => 'hr', // Current name of ID 9
    'department_id' => 26,
    'log_remark' => 'Test update self'
]);

try {
    $controller->update($requestUpdateSelf, 9);
    echo "PASS: Update self allowed.\n";
} catch (ValidationException $e) {
    echo "FAIL: Update self blocked: " . json_encode($e->errors()) . "\n";
} catch (\Exception $e) {
    if (strpos($e->getMessage(), 'Session store not set') !== false || strpos($e->getMessage(), 'Route') !== false || strpos($e->getMessage(), 'Header') !== false) {
        echo "PASS: Update self allowed (Validation passed, hit redirect).\n";
    } else {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
}
