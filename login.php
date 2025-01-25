<?php
require_once 'class.php';
session_name("NewSession");
session_start();

if (isset($_POST['login'])) {

	$db = new db_class();
	$username = $_POST['username'];
	$password = $_POST['password'];
	$get_id = $db->login($username, $password);

	if ($get_id['count'] > 0) {

		$_SESSION['user_id'] = $get_id['user_id'];
		unset($_SESSION['message']);

		echo "<script>window.location='index.php'</script>";
	} else {
		$_SESSION['message'] = "Invalid Username or Password";

		echo "<script>window.location='login-page.php'</script>";
	}
}
