<?php
$conn = mysqli_connect("127.0.0.1", "root", "");
$result = mysqli_query($conn, "SHOW DATABASES");
while ($row = mysqli_fetch_array($result)) {
    echo $row[0] . PHP_EOL;
}
mysqli_close($conn);
