<?php
require_once 'class.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if ($input) {
    $db = new db_class();
    $ltype_name = $input['ltype_name'];
    $ltype_desc = $input['ltype_desc'];

    $result = $db->save_ltype($ltype_name, $ltype_desc);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save the type']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
