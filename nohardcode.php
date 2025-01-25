<?php
date_default_timezone_set('Asia/Manila');
require_once 'session.php';
require_once 'class.php';

$db = new db_class();
$mysqli = $db->getConnection();

$query = "
    SELECT DISTINCT YEAR(date_released) AS year FROM project WHERE date_released IS NOT NULL ORDER BY year;
    SELECT DISTINCT sector FROM beneficiary WHERE sector IS NOT NULL ORDER BY sector;
    SELECT DISTINCT category FROM beneficiary WHERE category IS NOT NULL ORDER BY category;
    SELECT DISTINCT province FROM beneficiary WHERE province IS NOT NULL ORDER BY province;
    SELECT DISTINCT status FROM project WHERE status IS NOT NULL ORDER BY status;
    SELECT DISTINCT spin_no FROM project WHERE spin_no IS NOT NULL ORDER BY spin_no;
    SELECT DISTINCT acc_type FROM user WHERE acc_type IS NOT NULL ORDER BY acc_type;
";

$multi_result = $mysqli->multi_query($query);

$options = [];

$status_mapping = [
    0 => 'For Checking',
    1 => 'Approved',
    2 => 'Released',
    3 => 'Completed',
    4 => 'Denied',
    5 => 'Checked'
];

if ($multi_result) {
    do {
        if ($result = $mysqli->store_result()) {
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                if (isset($row['status'])) {
                    $row['status'] = $status_mapping[$row['status']];
                }
                $rows[] = $row;
            }
            $options[] = $rows;
            $result->free();
        }
    } while ($mysqli->next_result());
}

$mysqli->close();

echo json_encode($options);
