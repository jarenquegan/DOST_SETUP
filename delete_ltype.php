<?php
require_once 'class.php';
session_name("NewSession");
session_start();

if (isset($_REQUEST['ltype_id'])) {
	$ltype_id = $_REQUEST['ltype_id'];
	$db = new db_class();
	$db->delete_ltype($ltype_id);
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit();
}
