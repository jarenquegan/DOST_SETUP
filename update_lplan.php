<?php
require_once 'class.php';
if (isset($_POST['update'])) {
	$db = new db_class();
	$lplan_id = $_POST['lplan_id'];
	$lplan_month = $_POST['lplan_month'];
	$db->update_lplan($lplan_id, $lplan_month);

	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit();
}
