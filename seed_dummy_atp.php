<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "m_old");

// Insert dummy location
mysqli_query($conn, "INSERT INTO atps_list_locations (atp_id, location_name, classrooms_count) VALUES (2, 'Main Campus - Block A', 5)");

// Insert dummy learner enrollment
mysqli_query($conn, "INSERT INTO atps_list_le (atp_id, qualification_id, learners_no, cohort, start_date, end_date) VALUES (2, 1, '25', 'Batch 2024-A', '2024-01-01', '2024-12-31')");

// Insert dummy learner enrollment history
mysqli_query($conn, "INSERT INTO atps_learner_enrolled (atp_id, le1, le2, le3, le4, le5, le6, le7, le8, le9) VALUES (2, '100', '120', '150', '90', '110', '140', '160', '170', '180')");

echo "Dummy data seeded for ATP ID 2";
