<?php

date_default_timezone_set('Asia/Manila');
require_once 'class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['spin_no'])) {
    $db = new db_class();
    $spinNo = $_POST['spin_no'];

    $stmt = $db->conn->prepare("SELECT COUNT(*) as count FROM project WHERE spin_no = ?");
    if ($stmt === false) {

        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Failed to prepare SQL statement']);
        exit;
    }

    $stmt->bind_param("s", $spinNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {

        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Failed to execute SQL query']);
        exit;
    }

    $row = $result->fetch_assoc();

    $isUnique = ($row['count'] == 0);

    header('Content-Type: application/json');
    echo json_encode(['isUnique' => $isUnique]);

    $stmt->close();
} else {

    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Invalid request']);
}
