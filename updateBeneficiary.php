<?php
require_once 'class.php';
if (isset($_POST['update'])) {
    $db = new db_class();

    $beneficiary_id = $_POST['beneficiary_id'];
    $firmname = $_POST['firmname'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $tin = $_POST['tin'];
    $contact_no = $_POST['contact_no'];
    $tel_no = $_POST['tel_no'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $province = $_POST['province'];
    $sector = $_POST['sector'];
    $category = $_POST['category'];

    $db->update_beneficiary($beneficiary_id, $firmname, $firstname, $middlename, $lastname, $tin, $contact_no, $tel_no, $address, $email, $province, $sector, $category);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
