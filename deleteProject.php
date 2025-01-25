<?php
require_once 'class.php';
session_name("NewSession");
session_start();

if (isset($_REQUEST['project_id'])) {
	$project_id = $_REQUEST['project_id'];
	$db = new db_class();
	$db->delete_project($project_id);
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit();
}
