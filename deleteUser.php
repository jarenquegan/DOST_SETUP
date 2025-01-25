<?php
require_once 'class.php';
session_name("NewSession");
session_start();

if (isset($_REQUEST['user_id'])) {
	$user_id = $_REQUEST['user_id'];
	$db = new db_class();
	$db->delete_user($user_id);
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit();
}
