<?php
require_once 'class.php';

if (isset($_POST['confirm'])) {
    $db = new db_class();
    $username = $_POST['username'];

    if ($db->check_username_exists($username)) {
        echo "<script>alert('Username already taken. Please choose a different one.');</script>";
        echo "<script>window.history.back();</script>";
    } else {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];
        $acc_type = $_POST['acc_type'];
        $db->add_user($username, $password, $firstname, $lastname, $acc_type);

        echo "<script>window.history.back();</script>";
    }
}
