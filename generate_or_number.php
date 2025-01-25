<?php
date_default_timezone_set('Asia/Manila');
require_once 'session.php';
require_once 'class.php';
$db = new db_class();

if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];
    $or_number = $db->generateORNumber($project_id);
    echo $or_number;
} else {
    echo "Error: Project ID not provided";
}
