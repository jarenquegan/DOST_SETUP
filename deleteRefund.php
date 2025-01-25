<?php
date_default_timezone_set('Asia/Manila');
require_once 'class.php';

if (isset($_GET['project_sched_id'])) {
    $project_sched_id = intval($_GET['project_sched_id']);

    $db = new db_class();

    if ($project_sched_id > 0) {

        $db->conn->begin_transaction();

        try {

            $result = $db->conn->query("
                SELECT `project_id`, `status`
                FROM `project_schedule`
                WHERE `project_sched_id`='$project_sched_id'
            ") or die($db->conn->error);

            $row = $result->fetch_assoc();
            $project_id = $row['project_id'];
            $status = $row['status'];

            $selProject = $db->conn->query("
                SELECT `project_id`, `status`
                FROM `project`
                WHERE `project_id`='$project_id'
            ") or die($db->conn->error);

            $selRow = $selProject->fetch_assoc();
            $selProject_id = $selRow['project_id'];
            $selStatus = $selRow['status'];

            $db->conn->query("
                UPDATE `project_schedule`
                SET `status`='Unpaid'
                WHERE `project_sched_id`='$project_sched_id'
            ") or die($db->conn->error);

            $db->conn->query("
                DELETE FROM `refund`
                WHERE `project_schedule`='$project_sched_id'
            ") or die($db->conn->error);

            if ($status === 'Deferred') {
                $lastSchedResult = $db->conn->query("
                    SELECT MAX(`project_sched_id`) AS last_sched_id
                    FROM `project_schedule`
                    WHERE `project_id`='$project_id'
                ") or die($db->conn->error);

                $lastSchedRow = $lastSchedResult->fetch_assoc();
                $last_sched_id = $lastSchedRow['last_sched_id'];

                if ($last_sched_id && $last_sched_id !== $project_sched_id) {
                    $db->conn->query("
                        DELETE FROM `project_schedule`
                        WHERE `project_sched_id`='$last_sched_id'
                    ") or die($db->conn->error);
                }
            }

            if ($selStatus === '3') {
                $db->conn->query("
                    UPDATE `project`
                    SET `status`='2'
                    WHERE `project_id`='$project_id'
                ") or die($db->conn->error);

                $db->conn->query("
                    UPDATE `project_schedule`
                    SET `status`='Unpaid'
                    WHERE `project_sched_id`='$project_sched_id'
                ") or die($db->conn->error);
            }

            $db->conn->commit();

            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } catch (Exception $e) {

            $db->conn->rollback();
            die("Error occurred: " . $e->getMessage());
        }
    } else {
        die("Invalid project_sched_id.");
    }
} else {
    die("No project_sched_id provided.");
}
