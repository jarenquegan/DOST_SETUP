<?php
require_once 'class.php';
session_name("NewSession");
session_start();

if (isset($_REQUEST['beneficiary_id'])) {
	$beneficiary_id = $_REQUEST['beneficiary_id'];
	$db = new db_class();
	$db->delete_beneficiary($beneficiary_id);
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit();
}
