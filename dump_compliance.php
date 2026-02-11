<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$res = mysqli_query($conn, "SELECT * FROM atp_compliance LIMIT 20");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        print_r($row);
    }
} else {
    echo "Error: " . mysqli_error($conn);
}
mysqli_close($conn);
