<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
if (!$conn) die("fail");

$tables = ['atps_list', 'users_list', 'atps_list_le', 'atps_learner_enrolled'];
foreach($tables as $t) {
    $res = mysqli_query($conn, "SHOW TABLES LIKE '$t'");
    if(mysqli_num_rows($res) > 0) {
        $countRes = mysqli_query($conn, "SELECT COUNT(*) FROM $t");
        $count = mysqli_fetch_array($countRes)[0];
        echo "Table $t exists with $count records" . PHP_EOL;
    } else {
        echo "Table $t MISSING" . PHP_EOL;
    }
}
