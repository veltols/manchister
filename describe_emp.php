<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'm_old');
$res = mysqli_query($conn, 'DESCRIBE employees_list');
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . PHP_EOL;
}
