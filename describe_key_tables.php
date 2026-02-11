<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$tables = ['atps_eqa_details', 'atp_compliance', 'atps_sed_form', 'atps_form_init'];
foreach ($tables as $t) {
    echo "--- Table: $t ---\n";
    $res = mysqli_query($conn, "DESCRIBE $t");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            echo "{$row['Field']} ({$row['Type']}) - {$row['Null']} - {$row['Key']}\n";
        }
    } else {
        echo "Error: " . mysqli_error($conn) . "\n";
    }
}
mysqli_close($conn);
