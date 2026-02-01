<?php
$conn = mysqli_connect('127.0.0.1', 'root', '', 'm_old');
$res = mysqli_query($conn, "SELECT employee_id, department_id FROM employees_list WHERE employee_id = 40");
$row = mysqli_fetch_assoc($res);
print_r($row);
