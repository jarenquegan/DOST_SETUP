<?php
require_once 'class.php';
$db = new db_class();
session_name("NewSession");
session_start();
if (!($_SESSION['user_id'])) {
	header('location:login-page.php');
}

$user_data = $db->user_account($_SESSION['user_id']);
$account_type = $user_data['acc_type'];
$account_id = $user_data['user_id'];
