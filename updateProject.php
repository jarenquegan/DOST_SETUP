<?php
date_default_timezone_set('Asia/Manila');
require_once 'class.php';

if (isset($_POST['update'])) {
	$db = new db_class();
	$project_id = $_POST['project_id'];
	$beneficiary = $_POST['beneficiary_id'];
	$ltype = $_POST['ltype'];
	$lplan = $_POST['lplan'];
	$project_amount = $_POST['project_amount'];
	$purpose = $_POST['purpose'];
	$status = $_POST['status'];
	$title = $_POST['title'];
	$spin_no = $_POST['spin_no1'];
	$date_released = NULL;

	$tbl_project = $db->check_project($project_id);
	$fetch = $tbl_project->fetch_array();
	$tbl_lplan = $db->check_lplan($lplan);
	$fetch1 = $tbl_lplan->fetch_array();
	$month = $fetch1['lplan_month'];

	if (preg_match('/[1-9]/', $fetch['date_released'])) {
		$date_released = $fetch['date_released'];
	} else {
		if ($status == 2) {
			$date_released = $_POST['released_date'];

			for ($i = 1; $i <= $month; $i++) {
				$date_schedule = date("Y-m-d", strtotime($date_released . " +$i month"));

				$db->save_date_sched($project_id, $date_schedule);
			}
		} else {
			$date_released = NULL;
		}
	}

	$db->update_project($project_id, $beneficiary, $ltype, $lplan, $project_amount, $purpose, $status, $date_released, $title, $spin_no);

	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit();
}
