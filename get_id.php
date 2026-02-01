<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$res = mysqli_query($conn, "SELECT atp_id FROM atps_list LIMIT 1");
echo mysqli_fetch_array($res)[0];
