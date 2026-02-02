<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "manchester");
if (!$conn)
    die("Connection failed: " . mysqli_connect_error());

$tables = [
    'z_assets_list',
    'support_tickets_list',
    'hr_employees_leaves'
];

foreach ($tables as $table) {
    echo "------------------------------------------------\n";
    echo "TABLE: $table\n";
    echo "------------------------------------------------\n";
    $res = mysqli_query($conn, "DESCRIBE $table");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            echo str_pad($row['Field'], 25) . " | " . $row['Type'] . "\n";
        }
    } else {
        echo "Error describe $table: " . mysqli_error($conn) . "\n";
    }
    echo "\n";
}
?>