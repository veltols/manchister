<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");
$res = mysqli_query($conn, "SHOW TABLES LIKE 'atps_%'");
$tables = [];
while ($row = mysqli_fetch_row($res)) $tables[] = $row[0];
$res = mysqli_query($conn, "SHOW TABLES LIKE 'eqa_%'");
while ($row = mysqli_fetch_row($res)) $tables[] = $row[0];
$tables[] = 'atp_compliance';
$tables[] = 'quality_standard_main';

foreach ($tables as $t) {
    echo "--- Table: $t ---\n";
    $res = mysqli_query($conn, "DESCRIBE $t");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            echo "{$row['Field']} ({$row['Type']})\n";
        }
    } else {
        echo "Error: " . mysqli_error($conn) . "\n";
    }
}
mysqli_close($conn);
