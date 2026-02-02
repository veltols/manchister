<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "manchester");
if (!$conn)
    die("Connection failed: " . mysqli_connect_error());

$empId = 61;
$query = "SELECT * FROM employees_list WHERE employee_id = $empId";
$res = mysqli_query($conn, $query);

echo "Checking Employee ID: $empId\n";
if ($res && mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    print_r($row);
} else {
    echo "Employee not found in DB.\n";
}

echo "\nChecking ALL employees IDs:\n";
$resAll = mysqli_query($conn, "SELECT employee_id, first_name FROM employees_list");
while ($row = mysqli_fetch_assoc($resAll)) {
    echo $row['employee_id'] . ": " . $row['first_name'] . "\n";
}
?>