<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$tables = [
    'atps_list',
    'atps_sed_form',
    'atps_eqa_details',
    'atp_compliance',
    'atps_learner_enrolled',
    'atps_list_qualifications',
    'atps_list_faculties',
    'quality_standard_main'
];

foreach ($tables as $t) {
    echo "========================================\n";
    echo "TABLE: $t\n";
    echo "========================================\n";
    
    // Schema
    echo "SCHEMA:\n";
    $res = mysqli_query($conn, "DESCRIBE $t");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            echo "- {$row['Field']} ({$row['Type']})\n";
        }
    }
    
    // Row Count
    $res = mysqli_query($conn, "SELECT COUNT(*) FROM $t");
    if ($res) {
        $count = mysqli_fetch_row($res)[0];
        echo "\nROW COUNT: $count\n";
    }
    
    // Sample
    if ($count > 0) {
        echo "\nSAMPLE RECORD:\n";
        $res = mysqli_query($conn, "SELECT * FROM $t LIMIT 1");
        print_r(mysqli_fetch_assoc($res));
    }
    echo "\n\n";
}
mysqli_close($conn);
