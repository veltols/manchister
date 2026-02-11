<?php
$conn = mysqli_connect("127.0.0.1", "root", "");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$res = mysqli_query($conn, 'SHOW DATABASES');
if ($res) {
    while ($row = mysqli_fetch_row($res)) {
        echo $row[0] . PHP_EOL;
    }
} else {
    echo "Error: " . mysqli_error($conn);
}
mysqli_close($conn);
