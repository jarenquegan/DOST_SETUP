<?php
require_once 'class.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if ($input) {
    $db = new db_class();
    $lplan_month = $input['lplan_month'];

    $result = $db->save_lplan($lplan_month);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save the plan']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
