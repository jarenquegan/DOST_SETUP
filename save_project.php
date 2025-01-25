<?php
date_default_timezone_set('Asia/Manila');
require_once 'class.php';
if (isset($_POST['apply'])) {
	$db = new db_class();
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		$beneficiary = $_POST['beneficiary_id1'];
		$ltype = $_POST['ltype'];
		$lplan = $_POST['lplan'];
		$project_amount = $_POST['project_amount'];
		$purpose = $_POST['purpose'];
		$title = $_POST['title'];
		$date_created = date('Y-m-d');
		$spin_no = $_POST['spin_no'];

		$db = new db_class();

		$db->save_project($spin_no, $beneficiary, $ltype, $lplan, $project_amount, $purpose, $title, $date_created);

		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit();
	}
}
