<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$res = mysqli_query($conn, "DESCRIBE atps_form_init");
while ($row = mysqli_fetch_row($res)) {
    echo implode('|', $row) . PHP_EOL;
}
mysqli_close($conn);
