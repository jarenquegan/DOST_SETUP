<?php
require_once 'class.php';

if (isset($_POST['update_modal1'])) {
    $db = new db_class();
    $user_id = $_POST['user_id_modal1'];
    $username = $_POST['username_modal1'];
    $password = $_POST['password_modal1'];
    $firstname = $_POST['firstname_modal1'];
    $lastname = $_POST['lastname_modal1'];
    $acc_type = $_POST['acc_type_modal1'];

    $currentUser = $db->user_account($user_id);
    $existingUserPic = $currentUser['user_pic'];

    if ($db->check_username_exists_except_current($username, $user_id)) {
        echo "<script>alert('Username already taken. Please choose a different one.');</script>";
        echo "<script>window.history.back();</script>";
        exit();
    }

    if (!empty($_FILES['user_pic_modal1']['name'])) {
        $newUserPic = $_FILES['user_pic_modal1']['name'];
        $uploadPath = "assets/images/" . basename($newUserPic);

        if (move_uploaded_file($_FILES['user_pic_modal1']['tmp_name'], $uploadPath)) {
        } else {
            echo "<script>alert('Error uploading file');</script>";
            $newUserPic = $existingUserPic;
        }
    } elseif (isset($_POST['removedProfilePicture_modal1']) && $_POST['removedProfilePicture_modal1'] === 'circle-user-solid.svg') {
        $newUserPic = 'circle-user-solid.svg';
    } else {
        $newUserPic = $existingUserPic;
    }

    if ($db->update_user($user_id, $username, $password, $firstname, $lastname, $acc_type, $newUserPic)) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "<script>alert('Error updating user');</script>";
    }
}
