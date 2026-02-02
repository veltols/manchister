<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "manchester");
$res = mysqli_query($conn, 'SHOW TABLES');
while ($row = mysqli_fetch_row($res)) {
    echo $row[0] . PHP_EOL;
}
