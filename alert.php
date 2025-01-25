<?php
date_default_timezone_set('Asia/Manila');
require_once 'session.php';
require_once 'class.php';
$db = new db_class();

$user_data = $db->user_account($_SESSION['user_id']);

$request_count = $db->count_projects_by_status(0);
$checked_count = $db->count_projects_by_status(5);
$approved_count = $db->count_projects_by_status(1);

$request_alert_link = '';
$checked_alert_link = '';
$approved_alert_link = '';

if ($user_data) {
    $account_type = $user_data['acc_type'];

    if ($account_type == 'Regional Project Management Office (RPMO)' && $request_count > 0) {
        $request_alert_link = "
        <li class='nav-item'>
            <a class='nav-link' href='projects-request.php'>
                Alerts
                <span class='badge bg-warning'>$request_count</span>
            </a>
        </li>";
    }

    if ($account_type == 'Regional Director' && $checked_count > 0) {
        $checked_alert_link = "
        <li class='nav-item'>
            <a class='nav-link' href='projects-checked.php'>
                Alerts
                <span class='badge bg-purple'>$checked_count</span>
            </a>
        </li>";
    }

    if ($account_type == 'Cashier' && $approved_count > 0) {
        $approved_alert_link = "
        <li class='nav-item'>
            <a class='nav-link' href='projects-approved.php'>
                Alerts
                <span class='badge bg-info'>$approved_count</span>
            </a>
        </li>";
    }
}
