<?php
require_once 'class.php';
session_name("NewSession");
session_start();

if (isset($_REQUEST['lplan_id'])) {
	$lplan_id = $_REQUEST['lplan_id'];
	$db = new db_class();
	$db->delete_lplan($lplan_id);
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit();
}
