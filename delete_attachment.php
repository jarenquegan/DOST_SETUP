<?php
require_once 'class.php';
$db = new db_class();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['attachment_id'])) {
        $attachment_id = $_POST['attachment_id'];

        $attachment = $db->get_attachment_by_id($attachment_id);
        if ($attachment) {
            $file_path = "assets/attachments/" . $attachment['filename'];

            if (file_exists($file_path)) {
                unlink($file_path);
            }

            $db->delete_attachment($attachment_id);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Attachment not found']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid request']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
