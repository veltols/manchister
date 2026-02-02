<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'manchester');
$res = mysqli_query($conn, 'DESCRIBE employees_list');
while ($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . PHP_EOL;
}
