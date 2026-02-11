<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$res = mysqli_query($conn, "SELECT * FROM atps_list LIMIT 1");
if ($res) {
    print_r(mysqli_fetch_assoc($res));
} else {
    echo "Error: " . mysqli_error($conn);
}
mysqli_close($conn);
