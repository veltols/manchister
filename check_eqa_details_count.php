<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$res = mysqli_query($conn, "SELECT COUNT(*) FROM atps_eqa_details");
$count = mysqli_fetch_row($res)[0];
echo "atps_eqa_details has $count records.\n";
mysqli_close($conn);
