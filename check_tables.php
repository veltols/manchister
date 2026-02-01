<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$result = mysqli_query($conn, "SHOW TABLES");
while ($row = mysqli_fetch_array($result)) {
    echo $row[0] . PHP_EOL;
}
mysqli_close($conn);
