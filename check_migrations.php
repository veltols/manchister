<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$res = mysqli_query($conn, "SELECT migration FROM migrations ORDER BY id DESC LIMIT 20");
while ($row = mysqli_fetch_row($res)) {
    echo $row[0] . PHP_EOL;
}
mysqli_close($conn);
