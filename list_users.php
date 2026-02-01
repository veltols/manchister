<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$res = mysqli_query($conn, "SELECT user_email, user_type FROM users_list");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['user_email'] . " - " . $row['user_type'] . PHP_EOL;
}
