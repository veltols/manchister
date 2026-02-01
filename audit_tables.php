<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$res = mysqli_query($conn, "SHOW TABLES");
$allTables = [];
while($row = mysqli_fetch_array($res)) $allTables[] = $row[0];

$needed = [
    'atps_list_le', 
    'atps_learner_enrolled', 
    'atp_compliance', 
    'quality_standard_main', 
    'atps_sed_form', 
    'atps_eqa_details',
    'atps_list_locations',
    'atps_list_qualifications'
];

foreach($needed as $n) {
    if(in_array($n, $allTables)) {
        echo "FOUND: $n" . PHP_EOL;
    } else {
        echo "MISSING: $n" . PHP_EOL;
    }
}
