<?php
date_default_timezone_set('Asia/Manila');
require_once 'session.php';
require_once 'class.php';
$db = new db_class();

if (isset($_GET['term'])) {
    $term = $_GET['term'];
    $term = $db->conn->real_escape_string($term);

    $query = "SELECT project_id, spin_no FROM project WHERE spin_no LIKE '%$term%' AND status = 2";
    $result = $db->conn->query($query);

    $response = array();

    while ($row = $result->fetch_assoc()) {
        $response[] = array(
            'label' => $row['spin_no'],
            'value' => $row['project_id']
        );
    }

    echo json_encode($response);
} else {
    echo json_encode(array());
}
