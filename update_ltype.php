<?php
require_once 'class.php';
if (isset($_POST['update1'])) {
	$db = new db_class();
	$ltype_id = $_POST['ltype_id'];
	$ltype_name = $_POST['ltype_name'];
	$ltype_desc = $_POST['ltype_desc'];
	$db->update_ltype($ltype_id, $ltype_name, $ltype_desc);

	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit();
}
