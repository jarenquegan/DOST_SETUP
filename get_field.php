<?php
date_default_timezone_set('Asia/Manila');
require_once 'session.php';
require_once 'class.php';
$db = new db_class();

if (isset($_REQUEST['project_id'])) {
    $project_id = $_REQUEST['project_id'];
} else {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$tbl_project = $db->conn->query(
    "SELECT * FROM `project` 
     INNER JOIN `beneficiary` ON project.beneficiary_id = beneficiary.beneficiary_id 
     INNER JOIN `project_plan` ON project.lplan_id = project_plan.lplan_id 
     WHERE `project_id` = '$project_id'"
);
$fetch = $tbl_project->fetch_array();

$totalAmount = $fetch['amount'];
$monthly = $fetch['amount'] / $fetch['lplan_month'];

$refund = $db->conn->query("SELECT SUM(pay_amount) as total_refunded FROM `refund` WHERE `project_id` = '$project_id'") or die($db->conn->error);
$totalRefunded = $refund->fetch_assoc()['total_refunded'];

$balanceAmount = $totalAmount - $totalRefunded;

$response = array(
    "payee" => $fetch['lastname'] . ", " . $fetch['firstname'] . " " . substr($fetch['middlename'], 0, 1) . ".",
    "balance" => $balanceAmount,
    "payable" => $totalAmount,
    "monthly" => $fetch['amount'] / $fetch['lplan_month'],
    "monthlyAmount" => number_format($monthly, 2),
    "payableAmount" => number_format($totalAmount, 2),
    "balanceAmount" => number_format($balanceAmount, 2)
);

echo json_encode($response);
