<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$tables = ['atps_list_categories', 'atps_list_types', 'sys_countries_cities'];
foreach($tables as $t) {
    echo "--- $t ---" . PHP_EOL;
    $res = mysqli_query($conn, "DESCRIBE $t");
    while($row = mysqli_fetch_assoc($res)) {
        echo $row['Field'] . " - " . $row['Type'] . PHP_EOL;
    }
}
