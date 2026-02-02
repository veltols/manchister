<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "manchester");
if (!$conn)
    die("Connection failed: " . mysqli_connect_error());

$tables = ['hr_employees_leaves'];

foreach ($tables as $table) {
    echo "DESCRIBE $table:\n";
    $res = mysqli_query($conn, "DESCRIBE $table");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            echo $row['Field'] . " - " . $row['Type'] . "\n";
        }
    } else {
        echo "Error describe $table: " . mysqli_error($conn) . "\n";
    }
}
?>