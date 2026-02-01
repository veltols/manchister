<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$res = mysqli_query($conn, "SELECT pass_value FROM employees_list_pass WHERE is_active = 1");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['pass_value'] . PHP_EOL;
}
