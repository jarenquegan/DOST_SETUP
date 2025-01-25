<?php
require_once 'class.php';

if (isset($_POST['update'])) {
    $db = new db_class();
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $acc_type = $_POST['acc_type'];

    $currentUser = $db->user_account($user_id);
    $existingUserPic = $currentUser['user_pic'];

    if ($db->check_username_exists_except_current($username, $user_id)) {
        echo "<script>alert('Username already taken. Please choose a different one.');</script>";
        echo "<script>window.history.back();</script>";
        exit();
    }

    if (!empty($_FILES['user_pic']['name'])) {
        $newUserPic = $_FILES['user_pic']['name'];
        $uploadPath = "assets/images/" . basename($newUserPic);

        if (move_uploaded_file($_FILES['user_pic']['tmp_name'], $uploadPath)) {
            echo "<script>alert('Success uploading file');</script>";
        } else {
            echo "<script>alert('Error uploading file');</script>";
            $newUserPic = $existingUserPic;
        }
    } elseif (isset($_POST['removedProfilePicture']) && $_POST['removedProfilePicture'] === 'circle-user-solid.svg') {
        $newUserPic = 'circle-user-solid.svg';
    } else {
        $newUserPic = $existingUserPic;
    }

    if ($db->update_current_user($user_id, $username, $password, $firstname, $lastname, $acc_type, $newUserPic)) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "<script>alert('Error updating user');</script>";
    }
}
