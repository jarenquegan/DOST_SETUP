<?php
require_once 'class.php';
$db = new db_class();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $project_id = $_POST['project_id'];
        $target_dir = "assets/attachments/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = basename($_FILES["attachment"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {

            $file_type = mime_content_type($target_file);

            $db->save_attachment($project_id, $file_name, $file_type);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'File move failed']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'No file uploaded or file upload error']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
