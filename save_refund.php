<?php
date_default_timezone_set('Asia/Manila');
require_once 'class.php';

if (isset($_POST['save'])) {
    $db = new db_class();
    $project_id = $_POST['project_id'];
    $payee = $_POST['payee'];
    $payable = str_replace(",", "", $_POST['payable']);
    $refund = round(floatval($_POST['refund']), 2);
    $balance = floatval($_POST['balance']);
    $or_number = $_POST['or_number'];
    $monthly = round(floatval($_POST['monthly']), 2);
    $defer_payment = isset($_POST['defer_payment']);

    $result = $db->conn->query("
        SELECT MIN(project_sched_id) AS min_sched_id
        FROM `project_schedule`
        WHERE `project_id` = '$project_id' AND `status` = 'Unpaid'
        ") or die($db->conn->error);

    $row = $result->fetch_assoc();
    $min_sched_id = $row['min_sched_id'];

    if ($defer_payment) {
        if ($min_sched_id) {
            $db->conn->query("UPDATE `project_schedule` SET `status`='Deferred' WHERE `project_sched_id`='$min_sched_id'") or die($db->conn->error);
        }

        $db->save_refund($project_id, $payee, 0, 'Deferred', $min_sched_id);

        $last_sched_query = $db->conn->query("SELECT MAX(due_date) AS last_date FROM `project_schedule` WHERE `project_id`='$project_id'") or die($db->conn->error);
        $last_sched_row = $last_sched_query->fetch_assoc();
        $last_date = $last_sched_row['last_date'];

        if ($last_date) {
            $next_date = date("Y-m-d", strtotime($last_date . " +1 month"));
            $db->save_date_sched($project_id, $next_date);
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        if (($refund < $monthly)) {
            echo "<script>alert('Please enter a correct amount to pay!')</script>";
            echo "<script>window.location='refund.php'</script>";
        }

        if (($refund >= $balance) || ($refund >= $monthly)) {
            if ($db->conn->ping()) {

                if ($min_sched_id) {
                    $db->conn->query("UPDATE `project_schedule` SET `status`='Paid' WHERE `project_sched_id`='$min_sched_id'") or die($db->conn->error);
                }
                $db->save_refund($project_id, $payee, $refund, $or_number, $min_sched_id);

                $refundQuery = $db->conn->query("SELECT SUM(pay_amount) as total_refunded FROM `refund` WHERE `project_id` = '$project_id'") or die($db->conn->error);
                $totalRefunded = $refundQuery->fetch_assoc()['total_refunded'];

                if ($totalRefunded >= $payable) {
                    $db->conn->query("UPDATE `project` SET `status`='3' WHERE `project_id`='$project_id'") or die($db->conn->error);
                }

                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            } else {
                die("Database connection is not available.");
            }
        }
    }
}
