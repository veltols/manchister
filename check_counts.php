<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "manchester");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$tables = ['users', 'users_list', 'employees_list'];

foreach ($tables as $table) {
    try {
        $result = mysqli_query($conn, "SELECT COUNT(*) FROM `$table`");
        if ($result) {
            $row = mysqli_fetch_row($result);
            echo "$table: " . $row[0] . "\n";
        } else {
            echo "$table: Error - " . mysqli_error($conn) . "\n";
        }
    } catch (Exception $e) {
        echo "$table: Exception - " . $e->getMessage() . "\n";
    }
}
?>