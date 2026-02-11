<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$res = mysqli_query($conn, "SELECT COUNT(*) FROM eqa_008");
if ($res) {
    $row = mysqli_fetch_row($res);
    echo "eqa_008 has " . $row[0] . " records.\n";
} else {
    echo "Error checking eqa_008: " . mysqli_error($conn) . "\n";
}
mysqli_close($conn);
