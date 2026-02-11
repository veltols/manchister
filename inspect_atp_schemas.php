<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$tables = [
    'atps_list',
    'atps_sed_form',
    'atps_eqa_details',
    'atp_compliance',
    'atps_list_qualifications',
    'atps_list_faculties'
];

foreach ($tables as $t) {
    echo "--- TABLE: $t ---\n";
    $res = mysqli_query($conn, "DESCRIBE $t");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            echo "{$row['Field']} | {$row['Type']} | {$row['Null']} | {$row['Key']} | {$row['Default']} | {$row['Extra']}\n";
        }
    } else {
        echo "Error: " . mysqli_error($conn) . "\n";
    }
    echo "\n";
}
mysqli_close($conn);
