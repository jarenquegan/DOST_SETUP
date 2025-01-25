<?php
date_default_timezone_set('Asia/Manila');
require_once 'session.php';
require_once 'class.php';
require_once 'alert.php';
require_once 'footer.php';
$db = new db_class();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Projects</title>
    <link rel="shortcut icon" href="assets/images/DOST_1.png" type="image/x-icon">
    <link href='assets/boxicons/css/boxicons.min.css' rel='stylesheet'>
    <script src="assets/js/jquery.js"></script>
    <script src="js/loader.js"></script>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css" />
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <link href="assets/DataTables/dataTables.bootstrap5.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/main-style.css">
    <script src="assets/js/jspdf.umd.min.js"></script>
    <script src="assets/js/html2canvas.min.js"></script>
</head>

<body class="bg-light" id="page-top">
    <nav class="navbar navbar-expand-lg navbar-light bg-white navbar-fixed-top sticky-top" data-bs-theme="light">
        <div class="container-fluid">
            <a class="navbar-brand logo d-flex flex-row" href="index.php"><b>
                    D<span class="logo-color">O</span>ST</b>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="projects.php">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="refund.php">Refunds</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="beneficiary.php">Beneficiaries</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="setup_plan.php">Setup</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">Users</a>
                    </li>
                    <?php echo $request_alert_link; ?>
                    <?php echo $checked_alert_link; ?>
                    <?php echo $approved_alert_link; ?>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2 d-none d-lg-inline small">
                                <?php echo $db->user_acc($_SESSION['user_id']); ?>
                            </span>
                            <img class="img-profile rounded-circle" src="assets/images/<?php echo $user_data['user_pic']; ?>" alt="<?php echo $db->user_acc($_SESSION['user_id']); ?>" style="width: 35px; height: 35px; object-fit: cover;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#editUser<?php echo $user_data['user_id'] ?>">
                                    Edit Profile
                                </a>
                                <a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-3 bg-custom">
        <h3 class="text-center" style="color: white; padding: 2px;">REQUESTED SETUP PROJECTS</h3>
    </div>

    <div class="container-fluid mt-3">
        <div class="card shadow mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" style="width:100%" class="display compact table table-bordered table-striped table-condensed small">
                        <thead>
                            <tr class="fw-bold">
                                <th style="text-align: left;" class="content-fit text-start"></th>
                                <th style="text-align: left;">Beneficiary</th>
                                <th style="text-align: left;">Project Detail</th>
                                <th style="text-align: left;">Sector</th>
                                <th style="text-align: left;">Category</th>
                                <th style="text-align: left;">Cost</th>
                                <th style="text-align: left;">Year</th>
                                <th style="text-align: left;">Refund Detail</th>
                                <th style="text-align: left;">Status</th>
                                <th style="text-align: left;" class="content-fit">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tbl_project = $db->display_project_request();
                            $i = 1;
                            while ($fetch = $tbl_project->fetch_array()) {
                                $totalAmount = $fetch['amount'];
                                $monthly = $fetch['amount'] / $fetch['lplan_month'];

                                $refund = $db->conn->query("SELECT SUM(pay_amount) as total_refunded FROM `refund` WHERE `project_id` = '$fetch[project_id]'") or die($db->conn->error);
                                $totalRefunded = $refund->fetch_assoc()['total_refunded'];

                                $balance = $totalAmount - $totalRefunded;
                            ?>
                                <tr>
                                    <td style="text-align: left;" class="content-fit text-center"><?php echo $i++; ?></td>
                                    <td style="text-align: left;" class="content-min">
                                        Firm: <strong><?php echo $fetch['firmname'] ?></strong><br>
                                        Owner: <strong><?php echo $fetch['lastname'] . ", " . $fetch['firstname'] . " " . substr($fetch['middlename'], 0, 1) . "." ?></strong><br>
                                        TIN: <strong><?php echo substr($fetch['tin'], 0, 3) . "-" . substr($fetch['tin'], 3, 3) . "-" . substr($fetch['tin'], 6, 3) . "-" . substr($fetch['tin'], 9); ?>
                                        </strong><br>
                                        Contact: <strong><?php echo substr($fetch['contact_no'], 0, 4) . " " . substr($fetch['contact_no'], 4, 3) . " " . substr($fetch['contact_no'], 7, 4); ?></strong><br>
                                        Address: <strong><?php echo $fetch['address'] ?></strong>
                                    </td>
                                    <td style="text-align: left;" class="content-min">
                                        Project Title: <strong><?php echo $fetch['title'] ?></strong><br>
                                        Spin No.: <strong><?php echo $fetch['spin_no'] ?></strong><br>
                                        Setup Type: <strong><?php echo $fetch['ltype_name'] ?></strong><br>
                                        Project Duration: <strong><?php echo ($fetch['lplan_month'] / 12) . " years " ?></strong><br>
                                        Province: <strong><?php echo $fetch['province'] ?></strong><br>
                                        <?php
                                        $monthly = $fetch['amount'] / $fetch['lplan_month'];
                                        $totalAmount = $fetch['amount'];
                                        ?>
                                        <?php
                                        if (!empty($fetch['date_released']) && preg_match('/[1-9]/', $fetch['date_released'])) {
                                            echo 'Date Released: <strong>' . date("M d, Y", strtotime($fetch['date_released'])) . '</strong>';
                                        }
                                        ?>
                                    </td>
                                    <td style="text-align: left;" class="content-fit"><?php echo $fetch['sector'] ?></td>
                                    <td style="text-align: left;" class="content-fit"><?php echo $fetch['category'] ?></td>
                                    <td style="text-align: left;" class="content-fit"><strong><?php echo "&#8369; " . number_format($fetch['amount'], 2) ?></strong></td>
                                    <td style="text-align: left;" class="content-fit">
                                        <strong>
                                            <?php
                                            if (!empty($fetch['date_released']) && preg_match('/[1-9]/', $fetch['date_released'])) {
                                                echo date("Y", strtotime($fetch['date_released']));
                                            }
                                            ?>
                                        </strong>
                                    </td>
                                    <td style="text-align: left;" class="content-min">
                                        <?php
                                        $refundResult = $db->conn->query("SELECT * FROM `refund` WHERE `project_id`='$fetch[project_id]'") or die($db->conn->error);
                                        $refundedCount = $refundResult->num_rows;

                                        $offset = $refundedCount > 0 ? " OFFSET $refundedCount" : "";

                                        if ($fetch['status'] == 2 || $fetch['status'] == 3) {
                                            $nextScheduleResult = $db->conn->query("
                                                SELECT due_date 
                                                FROM `project_schedule` 
                                                WHERE `project_id`='$fetch[project_id]' 
                                                ORDER BY due_date ASC 
                                                LIMIT 1 $offset
                                            ") or die($db->conn->error);

                                            $nextSchedule = $nextScheduleResult->fetch_assoc();
                                            $next = $nextSchedule['due_date'] ?? null;

                                            if ($next) {
                                                $nextRefundDate = date('F d, Y', strtotime($next));
                                            } else {
                                                $nextRefundDate = "N/A";
                                            }

                                            $totalAmount = $totalAmount ?? 0;
                                            $balance = $balance ?? 0;

                                            $refundedAmount = number_format($totalAmount - $balance, 2);
                                            $balanceFormatted = number_format($balance, 2);

                                            $dueDateResult = $db->conn->query("
                                                SELECT MAX(due_date) AS last_date 
                                                FROM `project_schedule` 
                                                WHERE `project_id`='$fetch[project_id]'
                                            ") or die($db->conn->error);

                                            $dueDateRow = $dueDateResult->fetch_assoc();
                                            $dueDate = $dueDateRow['last_date'] ?? null;

                                            if ($dueDate) {
                                                $dueDateFormatted = date('F d, Y', strtotime($dueDate));
                                            } else {
                                                $dueDateFormatted = "N/A";
                                            }

                                            if ($fetch['status'] == 3) {
                                                $nextRefundDate = "N/A";
                                                $completeDateResult = $db->conn->query("
                                                    SELECT MAX(due_date) AS complete_date 
                                                    FROM `project_schedule` 
                                                    WHERE `project_id`='$fetch[project_id]' && `status`='Paid'
                                                ") or die($db->conn->error);
                                                $completeDateRow = $completeDateResult->fetch_assoc();
                                                $completeDate = $completeDateRow['complete_date'] ?? null;

                                                $completeDateFormatted = date('F d, Y', strtotime($completeDate));
                                            }
                                        ?>
                                            Next Refund Date: <strong><?= htmlspecialchars($nextRefundDate) ?></strong><br>
                                            Refunded Amount: <strong>&#8369; <?= htmlspecialchars($refundedAmount) ?></strong><br>
                                            Balance: <strong>&#8369; <?= htmlspecialchars($balanceFormatted) ?></strong><br>
                                            <?php if ($fetch['status'] == 3) { ?>
                                                Complete Date: <strong><?= htmlspecialchars($completeDateFormatted) ?></strong><br>
                                            <?php } else { ?>
                                                Due Date: <strong><?= htmlspecialchars($dueDateFormatted) ?></strong><br>
                                            <?php } ?>
                                        <?php
                                        }
                                        ?>
                                    </td>

                                    <td style="text-align: left;">
                                        <?php
                                        if ($fetch['status'] == 0) {
                                            echo '<span class="btn btn-sm btn-warning">For Checking</span>';
                                        } else if ($fetch['status'] == 1) {
                                            echo '<span class="btn btn-sm btn-info">Approved</span>';
                                        } else if ($fetch['status'] == 2) {
                                            echo '<span class="btn btn-sm btn-primary">Released</span>';
                                        } else if ($fetch['status'] == 3) {
                                            echo '<span class="btn btn-sm btn-success">Completed</span>';
                                        } else if ($fetch['status'] == 4) {
                                            echo '<span class="btn btn-sm btn-danger">Denied</span>';
                                        } else if ($fetch['status'] == 5) {
                                            echo '<span class="btn btn-sm btn-purple">Checked</span>';
                                        }
                                        ?>
                                    </td>
                                    <td style="text-align: left;" class="content-fit">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                                Action
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#attachmentModal<?php echo $fetch['project_id'] ?>">Manage Attachments</a></li>
                                                <?php
                                                if ($fetch['status'] == 2) {
                                                ?>
                                                    <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#subsidiaryLedger<?php echo $fetch['project_id'] ?>">Subsidiary Ledger</a></li>
                                                    <li><a class="dropdown-item small" href="#" id="printToPdfBtn<?php echo $fetch['project_id'] ?>">Print Subsidiary Ledger</a></li>
                                                    <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#deletebeneficiary<?php echo $fetch['project_id'] ?>">Delete</a></li>
                                                <?php
                                                } else if ($fetch['status'] == 3) {
                                                ?>
                                                    <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#subsidiaryLedger<?php echo $fetch['project_id'] ?>">Subsidiary Ledger</a></li>
                                                    <li><a class="dropdown-item small" href="#" id="printToPdfBtn<?php echo $fetch['project_id'] ?>">Print Subsidiary Ledger</a></li>
                                                    <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#deletebeneficiary<?php echo $fetch['project_id'] ?>">Delete</a></li>
                                                <?php
                                                } else {
                                                ?>
                                                    <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#updateproject<?php echo $fetch['project_id'] ?>" id="edit">Edit</a></li>
                                                    <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#deletebeneficiary<?php echo $fetch['project_id'] ?>">Delete</a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="deletebeneficiary<?php echo $fetch['project_id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title text-white">System Information</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">Are you sure you want to delete this record?</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                                <a class="btn btn-danger" href="deleteProject.php?project_id=<?php echo $fetch['project_id'] ?>">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php
    $tbl_project = $db->display_project_request();
    while ($fetch = $tbl_project->fetch_array()) {

        $totalRefundedAmount = 0;
        $tbl_schedule = $db->conn->query("
            SELECT COALESCE(SUM(r.pay_amount), 0) AS total_refunded
            FROM `refund` r
            WHERE r.project_id = '" . $fetch['project_id'] . "'
        ");
        if ($refundRow = $tbl_schedule->fetch_array()) {
            $totalRefundedAmount = $refundRow['total_refunded'];
        }

        $totalPayableAmount = $fetch['amount'];
        $project_balance = $totalPayableAmount - $totalRefundedAmount;
        $project_balanceFormatted = number_format($project_balance, 2);

        if ($totalPayableAmount > 0) {
            $refundRate = ($totalRefundedAmount / $totalPayableAmount) * 100;
        } else {
            $refundRate = 0;
        }
    ?>
        <div class="modal fade" id="subsidiaryLedger<?php echo $fetch['project_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h5 class="modal-title text-white">Subsidiary Ledger</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row align-items-center">
                                                <div class="col-md-8 text-start">
                                                    <h4><strong><?php echo strtoupper($fetch['title']); ?></strong></h4>
                                                    <i class="fa-regular fa-building"></i> <?php echo $fetch['firmname']; ?>&emsp;&emsp;&emsp;
                                                    <i class="fa-regular fa-user"></i> <?php echo ($fetch['firstname'] . " " . substr($fetch['middlename'], 0, 1) . ". " . $fetch['lastname']); ?>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <p>
                                                        Refund Progress<br>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar bg-success"
                                                            role="progressbar"
                                                            style="width: <?php echo $refundRate; ?>%;"
                                                            aria-valuenow="<?php echo $refundRate; ?>"
                                                            aria-valuemin="0"
                                                            aria-valuemax="100">
                                                            <?php echo number_format($refundRate, 2); ?>%
                                                        </div>
                                                    </div>
                                                    </p>
                                                </div>
                                            </div>
                                            <div style="text-align: right;">Project Spin No.:
                                                <strong><?php echo $fetch['spin_no']; ?></strong>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row text-start">
                                        <div class="col-md-3">
                                            <span>Amount Assisted</span><br>
                                            <span><strong><?php echo "&#8369; " . number_format($fetch['amount'], 2) ?></strong></span>
                                        </div>
                                        <div class="col-md-3">
                                            <span>Monthly Refund</span><br>
                                            <span><strong><?php echo "&#8369; " . number_format($fetch['amount'] / $fetch['lplan_month'], 2) ?></strong></span>
                                        </div>
                                        <div class="col-md-3">
                                            <span>Refunded</span><br>
                                            <span><strong>&#8369; <?= htmlspecialchars($totalRefundedAmount) ?></strong></span>
                                        </div>
                                        <div class="col-md-3">
                                            <span>Balance</span><br>
                                            <span><strong>&#8369; <?= htmlspecialchars($project_balanceFormatted) ?></strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <table id="refundSched" style="width:100%" class="display compact table table-bordered table-striped table-condensed small">
                                <thead style="background-color: lightgray;">
                                    <tr class="fw-bold text-white">
                                        <th>Date Scheduled</th>
                                        <th>Amount Refunded</th>
                                        <th>OR No.</th>
                                        <th>Balance</th>
                                        <th style="text-align: left;" class="content-fit">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tbl_schedule = $db->conn->query("
                                            SELECT ps.*, r.or_number,
                                                COALESCE(r.pay_amount, 0) AS refunded_amount
                                            FROM `project_schedule` ps
                                            LEFT JOIN `refund` r 
                                                ON ps.project_sched_id = r.project_schedule 
                                                AND ps.project_id = r.project_id
                                                AND r.project_schedule = ps.project_sched_id
                                            WHERE ps.project_id = '" . $fetch['project_id'] . "'
                                        ");

                                    $totalPayableAmount = $fetch['amount'];
                                    $remainingBalance = $totalPayableAmount;

                                    while ($row = $tbl_schedule->fetch_array()) {
                                        $refundedAmount = $row['refunded_amount'];

                                        $remainingBalance -= $refundedAmount;

                                        $statusBadge = "";
                                        switch ($row['status']) {
                                            case 'Paid':
                                                $statusBadge = '<span class="badge text-bg-success"><i class="fa-solid fa-check"></i></span>';
                                                break;
                                            case 'Unpaid':
                                                $statusBadge = '<span class="badge text-bg-danger"><i class="fa-solid fa-xmark"></i></span>';
                                                break;
                                            case 'Deferred':
                                                $statusBadge = '<span class="badge text-bg-secondary"><i class="fa-solid fa-minus"></i></span>';
                                                break;
                                            default:
                                                $statusBadge = '<span class="badge text-bg-secondary">Unknown</span>';
                                                break;
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo date("M d, Y", strtotime($row['due_date'])); ?></td>
                                            <td>
                                                <?php
                                                $refundedAmount = $row['refunded_amount'];
                                                if ($refundedAmount == 0) {
                                                    echo "-";
                                                } else {
                                                    echo "&#8369; " . number_format($refundedAmount, 2);
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if (isset($row['status']) && ($row['status'] === 'Deferred' || $row['status'] === '')) {
                                                    echo "-";
                                                } else {
                                                    echo isset($row['or_number']) ? $row['or_number'] : '-';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['status'] == 'Paid') {
                                                    echo "&#8369; " . number_format(max($remainingBalance, 0), 2);
                                                } else {
                                                    echo "-";
                                                }
                                                ?>
                                            </td>
                                            <td style="text-align: middle;" class="content-fit text-center"><?php echo $statusBadge; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {

                document.querySelectorAll("[id^=printToPdfBtn]").forEach(button => {
                    button.addEventListener("click", function(e) {
                        e.preventDefault();

                        const projectId = this.id.replace("printToPdfBtn", "");

                        const modalContent = document.querySelector(`#subsidiaryLedger${projectId} .modal-body`).innerHTML;

                        const printTab = window.open('', '_blank');

                        printTab.document.write(`
                <html>
                    <head>
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Subsidiary Ledger - Project ${projectId}</title>
                        <link rel="shortcut icon" href="assets/images/DOST_1.png" type="image/x-icon">
                        <link rel="shortcut icon" href="assets/images/DOST_1.png" type="image/x-icon">
                        <link href='assets/boxicons/css/boxicons.min.css' rel='stylesheet'>
                        <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
                        <link href="assets/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
                        <link href="assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css" />
                        <style>
                            body, html {
                                margin: 0;
                                margin-top: 0.1in;
                                padding: 0;
                                width: 100%;
                                height: 100%;
                            }
                            .container-fluid {
                                margin: 0;
                                padding: 0;
                                width: 100%;
                                height: auto;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                            }
                            td, th {
                                padding: 5px;
                                word-wrap: break-word;
                            }

                           
                            @media print {
                                html, body {
                                    margin-top: 0in;
                                    width: 100%;
                                    height: auto;
                                    zoom: 1;
                                    font-size: 12px;
                                }
                                .container-fluid {
                                    width: 100%;
                                    height: auto;
                                    margin: 0;
                                    padding: 0;
                                }
                                table {
                                    width: 100%;
                                }
                                td, th {
                                    padding: 5px;
                                    word-wrap: break-word;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container-fluid">
                            ${modalContent}
                        </div>
                    </body>
                </html>
            `);

                        printTab.document.close();
                        printTab.focus();

                        setTimeout(function() {
                            printTab.print();
                        }, 300);
                    });
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#refundSched').on('shown.bs.modal', function() {
                    var tableElement = $(this).find('#refundSched');

                    if ($.fn.DataTable.isDataTable(tableElement)) {

                        tableElement.DataTable().clear().destroy();
                    }

                    tableElement.DataTable({
                        paging: true,
                        ordering: false,
                        searching: false,
                        info: false,
                        lengthChange: false,
                        pageLength: 12
                    });
                });
            });
        </script>

        <div class="modal fade" id="updateproject<?php echo $fetch['project_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form method="POST" action="updateProject.php">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title text-white">Edit Project</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <input type="hidden" name="project_id" value="<?php echo $fetch['project_id']; ?>">

                                <div class="col-md-6">
                                    <label for="beneficiary_name_<?php echo $fetch['project_id']; ?>" class="form-label">Beneficiary</label>
                                    <input list="beneficiary_list_<?php echo $fetch['project_id']; ?>"
                                        id="beneficiary_name_<?php echo $fetch['project_id']; ?>"
                                        name="beneficiary_name"
                                        class="form-control beneficiary-input"
                                        required
                                        value="<?php echo $fetch['lastname'] . ', ' . $fetch['firstname'] . ' ' . substr($fetch['middlename'], 0, 1)  . ". - " .  $fetch['firmname'] ?>"
                                        data-project-id="<?php echo $fetch['project_id']; ?>">
                                    <datalist id="beneficiary_list_<?php echo $fetch['project_id']; ?>">
                                        <?php
                                        $tbl_beneficiary = $db->display_beneficiary();
                                        while ($row = $tbl_beneficiary->fetch_array()) {
                                        ?>
                                            <option data-id="<?php echo $row['beneficiary_id'] ?>" value="<?php echo $row['lastname'] . ", " . $row['firstname'] . " " . substr($row['middlename'], 0, 1)  . ". - " .  $row['firmname'] ?>"></option>
                                        <?php
                                        }
                                        ?>
                                    </datalist>
                                    <input type="hidden" name="beneficiary_id" id="beneficiary_id_<?php echo $fetch['project_id']; ?>" value="<?php echo $fetch['beneficiary_id']; ?>">
                                </div>

                                <div class="col-md-6">
                                    <label for="ltype" class="form-label">Project Type</label>
                                    <select name="ltype" class="form-select" required>
                                        <?php
                                        $tbl_ltype = $db->display_ltype();
                                        while ($row = $tbl_ltype->fetch_array()) {
                                        ?>
                                            <option value="<?php echo $row['ltype_id'] ?>" <?php echo ($fetch['ltype_id'] == $row['ltype_id']) ? 'selected' : '' ?>><?php echo $row['ltype_name'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="lplan" class="form-label">Setup Plan</label>
                                    <select name="lplan" class="form-select" id="ulplan" required>
                                        <?php
                                        $tbl_lplan = $db->display_lplan();
                                        while ($row = $tbl_lplan->fetch_array()) {
                                        ?>
                                            <option value="<?php echo $row['lplan_id'] ?>" <?php echo ($fetch['lplan_id'] == $row['lplan_id']) ? 'selected' : '' ?>>
                                                <?php echo ($row['lplan_month'] / 12) . " years " ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="project_amount" class="form-label">Project Amount</label>
                                    <input type="number" name="project_amount" class="form-control" id="uamount" value="<?php echo $fetch['amount'] ?>" required />
                                </div>
                                <div class="col-md-12">
                                    <label for="title" class="form-label">Title</label>
                                    <input name="title" class="form-control" value="<?php echo $fetch['title'] ?>" required />
                                </div>

                                <div class="col-md-10">
                                    <label for="spin_no_other_<?php echo $fetch['project_id']; ?>" class="form-label">Spin No.</label>
                                    <input type="text" name="spin_no1" class="form-control" id="spin_no_other_<?php echo $fetch['project_id']; ?>" value="<?php echo htmlspecialchars($fetch['spin_no'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-primary w-100 generate-spin" data-target="<?php echo $fetch['project_id']; ?>">Generate</button>
                                </div>

                                <div class="col-md-12">
                                    <label for="purpose" class="form-label">Purpose</label>
                                    <textarea name="purpose" class="form-control" required><?php echo $fetch['purpose'] ?></textarea>
                                </div>
                                <div class="col-md-12 d-flex align-items-end">
                                    <button type="button" class="btn btn-warning w-100" id="updateCalculate_<?php echo $fetch['project_id']; ?>">Calculate Amount</button>
                                </div>
                            </div>
                            <hr id="line1">
                            <div class="row text-center" id="calcTable1">
                                <div class="col-md-6">
                                    <span>Total Payable Amount</span><br>
                                    <span id="utpa"><?php echo "&#8369; " . number_format($fetch['amount'], 2) ?></span>
                                </div>
                                <div class="col-md-6">
                                    <span>Monthly Payable Amount</span><br>
                                    <span id="umpa"><?php echo "&#8369; " . number_format($fetch['amount'] / $fetch['lplan_month'], 2) ?></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <?php
                                    $user_data = $db->user_account($_SESSION['user_id']);
                                    $current_status = $fetch['status'];
                                    $selected_value = isset($current_status) ? $current_status : '';
                                    ?>
                                    <input type="hidden" name="status" value="<?php echo htmlspecialchars($selected_value); ?>">
                                    <select class="form-select" name="status" id="statusSelect_<?php echo $fetch['project_id']; ?>">
                                        <?php
                                        if ($user_data) {
                                            $account_type = $user_data['acc_type'];

                                            if ($account_type == 'Administrator') {
                                        ?>
                                                <option value="0" <?php echo ($selected_value == 0) ? 'selected' : '' ?>>For Checking</option>
                                                <option value="1" <?php echo ($selected_value == 1) ? 'selected' : '' ?>>Approved</option>
                                                <option value="2" <?php echo ($selected_value == 2) ? 'selected' : '' ?>>Released</option>
                                                <option value="4" <?php echo ($selected_value == 4) ? 'selected' : '' ?>>Denied</option>
                                                <option value="5" <?php echo ($selected_value == 5) ? 'selected' : '' ?>>Checked</option>
                                            <?php
                                            } else if ($account_type == 'Setup Officer') {
                                            ?>
                                                <option value="0" <?php echo ($selected_value == 0) ? 'selected' : '' ?> disabled>For Checking</option>
                                                <option value="1" <?php echo ($selected_value == 1) ? 'selected' : '' ?> disabled>Approved</option>
                                                <option value="2" <?php echo ($selected_value == 2) ? 'selected' : '' ?> disabled>Released</option>
                                                <option value="4" <?php echo ($selected_value == 4) ? 'selected' : '' ?> disabled>Denied</option>
                                                <option value="5" <?php echo ($selected_value == 5) ? 'selected' : '' ?> disabled>Checked</option>
                                            <?php
                                            } else if ($account_type == 'Regional Project Management Office (RPMO)') {
                                                $disable_options = ($current_status == 1) ? 'disabled' : '';
                                            ?>
                                                <option value="<?= $selected_value; ?>" disabled selected>-</option>
                                                <option value="0" <?php echo ($selected_value == 0) ? 'selected' : '' ?> <?= $disable_options ?>>For Checking</option>
                                                <option value="5" <?php echo ($selected_value == 5) ? 'selected' : '' ?> <?= $disable_options ?>>Checked</option>
                                            <?php
                                            } else if ($account_type == 'Regional Director') {
                                                $disable_options = ($current_status != 5 && $current_status != 4) ? 'disabled' : '';
                                            ?>
                                                <option value="<?= $selected_value; ?>" disabled selected>-</option>
                                                <option value="1" <?php echo ($selected_value == 1) ? 'selected' : '' ?> <?= $disable_options ?>>Approved</option>
                                                <option value="4" <?php echo ($selected_value == 4) ? 'selected' : '' ?> <?= $disable_options ?>>Denied</option>
                                            <?php
                                            } else if ($account_type == 'Cashier') {
                                                $disable_options = ($current_status != 1) ? 'disabled' : '';
                                            ?>
                                                <option value="<?= $selected_value; ?>" disabled selected>-</option>
                                                <option value="2" <?php echo ($selected_value == 2) ? 'selected' : '' ?> <?= $disable_options ?>>Released</option>
                                            <?php
                                            } else {
                                            ?>
                                                <option disabled>Invalid account type</option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-10" id="releasedDateDiv_<?php echo $fetch['project_id']; ?>" style="display: none;">
                                    <label for="released_date" class="form-label">Released Date</label>
                                    <input type="date" name="released_date" class="form-control" id="releasedDate_<?php echo $fetch['project_id']; ?>" />
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-primary w-100" id="currentDateBtn_<?php echo $fetch['project_id']; ?>" style="display: none;">Date Now</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="update" class="btn btn-warning">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var statusSelect = document.getElementById('statusSelect_<?php echo $fetch['project_id']; ?>');
                var releasedDateDiv = document.getElementById('releasedDateDiv_<?php echo $fetch['project_id']; ?>');
                var releasedDateInput = document.getElementById('releasedDate_<?php echo $fetch['project_id']; ?>');
                var currentDateBtn = document.getElementById('currentDateBtn_<?php echo $fetch['project_id']; ?>');

                function toggleReleasedDate() {
                    if (statusSelect.value == '2') {
                        releasedDateDiv.style.display = 'block';
                        currentDateBtn.style.display = 'block';
                    } else {
                        releasedDateDiv.style.display = 'none';
                        currentDateBtn.style.display = 'none';
                        releasedDateInput.value = '';
                    }
                }

                toggleReleasedDate();

                statusSelect.addEventListener('change', toggleReleasedDate);

                currentDateBtn.addEventListener('click', function() {
                    var currentDate = new Date().toISOString().split('T')[0];
                    releasedDateInput.value = currentDate;
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const beneficiaryInputs = document.querySelectorAll('.beneficiary-input');

                beneficiaryInputs.forEach(input => {
                    const projectId = input.getAttribute('data-project-id');
                    const beneficiaryIdInput = document.getElementById(`beneficiary_id_${projectId}`);
                    const datalist = document.getElementById(`beneficiary_list_${projectId}`);

                    input.addEventListener('input', function() {
                        const selectedOption = Array.from(datalist.options).find(option => option.value === this.value);
                        if (selectedOption) {
                            const beneficiaryId = selectedOption.getAttribute('data-id');
                            beneficiaryIdInput.value = beneficiaryId;
                            console.log(`Project ${projectId}: Beneficiary ID set to ${beneficiaryId}`);
                        } else {
                            beneficiaryIdInput.value = '';
                            console.log(`Project ${projectId}: Beneficiary ID cleared`);
                        }
                    });

                    const initialOption = Array.from(datalist.options).find(option => option.value === input.value);
                    if (initialOption) {
                        beneficiaryIdInput.value = initialOption.getAttribute('data-id');
                        console.log(`Project ${projectId}: Initial Beneficiary ID set to ${beneficiaryIdInput.value}`);
                    }
                });
            });
        </script>

        <!-- Modal for Attachments -->
        <div class="modal fade" id="attachmentModal<?php echo $fetch['project_id'] ?>" tabindex="-1" aria-labelledby="attachmentModalLabel<?php echo $fetch['project_id'] ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white" id="attachmentModalLabel<?php echo $fetch['project_id'] ?>">Manage Attachments</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row align-items-center">
                                                <div class="col-md-8 text-start">
                                                    <h4><strong><?php echo strtoupper($fetch['title']); ?></strong></h4>
                                                    <i class="fa-regular fa-building"></i> <?php echo $fetch['firmname']; ?>&emsp;&emsp;&emsp;
                                                    <i class="fa-regular fa-user"></i> <?php echo ($fetch['firstname'] . " " . substr($fetch['middlename'], 0, 1) . ". " . $fetch['lastname']); ?>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <p>
                                                        Refund Progress<br>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar bg-success"
                                                            role="progressbar"
                                                            style="width: <?php echo $refundRate; ?>%;"
                                                            aria-valuenow="<?php echo $refundRate; ?>"
                                                            aria-valuemin="0"
                                                            aria-valuemax="100">
                                                            <?php echo number_format($refundRate, 2); ?>%
                                                        </div>
                                                    </div>
                                                    </p>
                                                </div>
                                            </div>
                                            <div style="text-align: right;">Project Spin No.:
                                                <strong><?php echo $fetch['spin_no']; ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- File upload form -->
                        <form id="uploadForm<?php echo $fetch['project_id'] ?>" enctype="multipart/form-data" method="POST">
                            <input type="hidden" name="project_id" value="<?php echo $fetch['project_id'] ?>">
                            <div class="mb-3">
                                <label for="fileInput<?php echo $fetch['project_id'] ?>" class="form-label">Upload Attachment</label>
                                <input class="form-control" type="file" name="attachment" id="fileInput<?php echo $fetch['project_id'] ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Upload</button>
                        </form>

                        <hr>
                        <label class="form-label">Existing Attachments</label>
                        <div class="container">
                            <table id="attachmentsTable" style="width:100%" class="display compact table table-bordered table-striped table-condensed small">
                                <thead style="background-color: lightgray;">
                                    <tr class="fw-bold text-white">
                                        <th>File Name</th>
                                        <th>File Type</th>
                                        <th>Date Added</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $attachments = $db->get_attachments($fetch['project_id']);
                                    if ($attachments) {
                                        foreach ($attachments as $attachment) {
                                            $file_type = $attachment['file_type'] ?? 'Unknown';
                                            $date_added = date('M d, Y', strtotime($attachment['uploaded_at']));
                                    ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($attachment['filename'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($file_type); ?></td>
                                                <td><?php echo $date_added; ?></td>
                                                <td style="text-align: left;" class="content-fit">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="assets/attachments/<?php echo htmlspecialchars($attachment['filename'] ?? ''); ?>" download class="dropdown-item small">Download</a></li>
                                                            <li><a class="dropdown-item small" href="#" onclick="deleteAttachment(<?php echo $attachment['id']; ?>)">Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="4" class="text-center">No attachments available.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function deleteAttachment(attachmentId) {

                fetch('delete_attachment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'attachment_id=' + encodeURIComponent(attachmentId)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Attachment deleted successfully');

                            location.reload();
                        } else {
                            alert('Failed to delete attachment: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the attachment');
                    });
            }
        </script>

        <script>
            document.getElementById('uploadForm<?php echo $fetch['project_id'] ?>').addEventListener('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                fetch('upload_attachment.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('File uploaded successfully');
                            location.reload();
                        } else {
                            alert('File upload failed: ' + data.error);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        </script>
    <?php } ?>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="save_project.php">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Project Application</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="beneficiary1" class="form-label">Beneficiary</label>
                                <input list="beneficiary_list1" class="form-control" id="beneficiary1" required>
                                <datalist id="beneficiary_list1">
                                    <option value=""></option>
                                    <?php
                                    $tbl_beneficiary = $db->display_beneficiary();
                                    while ($fetch = $tbl_beneficiary->fetch_array()) {
                                    ?>
                                        <option data-id="<?php echo $fetch['beneficiary_id'] ?>" value="<?php echo $fetch['lastname'] . ", " . $fetch['firstname'] . " " . substr($fetch['middlename'], 0, 1) . ". - " .  $fetch['firmname'] ?>"></option>
                                    <?php
                                    }
                                    ?>
                                </datalist>
                                <input type="hidden" name="beneficiary_id1" id="beneficiary_id1">
                            </div>

                            <script>
                                document.getElementById('beneficiary1').addEventListener('input', function() {
                                    let beneficiaryValue = this.value;
                                    let options = document.getElementById('beneficiary_list1').options;
                                    for (let i = 0; i < options.length; i++) {
                                        if (options[i].value === beneficiaryValue) {
                                            let beneficiaryId = options[i].getAttribute('data-id');
                                            document.getElementById('beneficiary_id1').value = beneficiaryId;
                                            console.log("Beneficiary ID set to: ", beneficiaryId);
                                            break;
                                        }
                                    }
                                });
                            </script>

                            <div class="col-md-6">
                                <label for="ltype" class="form-label">Project Type</label>
                                <select name="ltype" class="form-select" required>
                                    <option value=""></option>
                                    <?php
                                    $tbl_ltype = $db->display_ltype();
                                    $first = true;
                                    while ($fetch = $tbl_ltype->fetch_array()) {
                                        $selected = $first ? 'selected' : '';
                                        $first = false;
                                    ?>
                                        <option value="<?php echo $fetch['ltype_id']; ?>" <?php echo $selected; ?>>
                                            <?php echo $fetch['ltype_name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="lplan" class="form-label">Setup Plan</label>
                                <select name="lplan" class="form-select" id="lplan" required>
                                    <option value="">Please select an option</option>
                                    <?php
                                    $tbl_lplan = $db->display_lplan();
                                    while ($fetch = $tbl_lplan->fetch_array()) {
                                    ?>
                                        <option value="<?php echo $fetch['lplan_id'] ?>">
                                            <?php echo ($fetch['lplan_month'] / 12) . " years " ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="project_amount" class="form-label">Project Amount</label>
                                <input type="number" name="project_amount" class="form-control" id="amount" required />
                            </div>
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title</label>
                                <input name="title" class="form-control" required />
                            </div>

                            <div class="col-md-10" id="" style="display: block;">
                                <label for="spin_no" class="form-label">Spin No.</label>
                                <input type="text" name="spin_no" class="form-control" id="spin_no" required />
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" id="generate_spin" style="display: block;">Generate</button>
                            </div>

                            <div class="col-md-12">
                                <label for="purpose" class="form-label">Purpose</label>
                                <textarea name="purpose" class="form-control" required></textarea>
                            </div>
                            <div class="col-md-12 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" id="calculate">Calculate Amount</button>
                            </div>
                        </div>
                        <hr id="line">
                        <div class="row text-center" id="calcTable">
                            <div class="col-md-6">
                                <span>Total Payable Amount</span><br>
                                <span id="tpa"></span>
                            </div>
                            <div class="col-md-6">
                                <span>Monthly Payable Amount</span><br>
                                <span id="mpa"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="apply">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $("#calculate").click(function() {
                calculateAmount("#lplan", "#amount", "#tpa", "#mpa");
                $("#line").show();
                $("#calcTable").show();
            });

            $(document).on('click', '[id^="updateCalculate_"]', function() {
                var modalId = $(this).closest('.modal').attr('id');
                calculateAmount("#" + modalId + " #ulplan", "#" + modalId + " #uamount", "#" + modalId + " #utpa", "#" + modalId + " #umpa");
            });

            function calculateAmount(planSelector, amountSelector, tpaSelector, mpaSelector) {
                if ($(planSelector).val() == "" || $(amountSelector).val() == "") {
                    alert("Please enter a Setup Plan or Amount to Calculate");
                } else {
                    var lplan = $(planSelector + " option:selected").text();
                    var months = parseFloat(lplan.split('months')[0].trim()) * 12;
                    console.log("Months:", months);
                    var amount = parseFloat($(amountSelector).val());
                    var monthly = amount / months;
                    var totalAmount = amount;
                    console.log("Monthly:", monthly);
                    console.log("Total Amount:", totalAmount);
                    $(tpaSelector).text("\u20B1 " + totalAmount.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $(mpaSelector).text("\u20B1 " + monthly.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                }
            }

            $("#edit").click(function() {
                $("#line1").show();
                $("#calcTable1").show();
            });

            $("#add").click(function() {
                $("#line").hide();
                $("#calcTable").hide();
            });
        });
    </script>

    <a class="scroll-to-top rounded-circle" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div id="container-floating">
        <div id="floating-button">
            <button type="button" class="custom-btn" data-bs-toggle="modal" data-bs-target="#addModal" id="add"
                <?php echo ($account_type != 'Administrator' && $account_type != 'Setup Officer') ? 'disabled' : ''; ?>>
                <p class="plus">
                    <i class="fa-solid fa-plus"></i>
                </p>
            </button>
        </div>
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">System Information</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Are you sure you want to end this session?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUser<?php echo $user_data['user_id']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" id="editUserForm1<?php echo $user_data['user_id']; ?>" action="updateCurrentUser.php" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title text-white">Edit User</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 justify-content-center">
                            <input type="hidden" name="user_id" value="<?php echo $user_data['user_id']; ?>" />

                            <div class="col-12">
                                <div class="d-flex justify-content-center">
                                    <img id="previewImage" src="assets/images/<?php echo $user_data['user_pic']; ?>" class="rounded-circle img-thumbnail" alt="Profile Preview" style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-center">
                                    <strong>
                                        <h4 id="userFullName" class="form-label d-flex justify-content-center"><?php echo $user_data['firstname'] . " " . $user_data['lastname']; ?></h4>
                                    </strong>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-center mb-3">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeProfilePicture()">Remove Profile Picture</button>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="user_pic" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="user_pic" name="user_pic" accept="image/*" />
                            </div>

                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" value="<?php echo $user_data['username']; ?>" required />
                            </div>

                            <div class="col-md-6">
                                <label for="firstname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $user_data['firstname']; ?>" required />
                            </div>

                            <div class="col-md-6">
                                <label for="lastname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $user_data['lastname']; ?>" required />
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password2" name="password" value="<?php echo $user_data['password']; ?>" required />
                            </div>

                            <div class="col-md-12" id="password-error2" style="color:red; display:none; text-align:center;">
                                Error: The password must be at least 8 characters long, include uppercase and lowercase letters, a number, and a symbol.
                            </div>

                            <?php if ($account_type != 'Administrator') { ?>
                                <input type="hidden" name="acc_type" value="<?php echo htmlspecialchars($user_data['acc_type']); ?>">
                            <?php } ?>
                            <?php if ($account_type == 'Administrator') { ?>
                                <div class="col-md-12">
                                    <label for="acc_type" class="form-label">Account Type</label>
                                    <select class="form-select" name="acc_type" required>
                                        <option value="Administrator" <?php echo ($user_data['acc_type'] == 'Administrator') ? 'selected' : '' ?>>Administrator</option>
                                        <option value="Setup Officer" <?php echo ($user_data['acc_type'] == 'Setup Officer') ? 'selected' : '' ?>>Setup Officer</option>
                                        <option value="Regional Project Management Office (RPMO)" <?php echo ($user_data['acc_type'] == 'Regional Project Management Office (RPMO)') ? 'selected' : '' ?>>Regional Project Management Office (RPMO) (Checker)</option>
                                        <option value="Regional Director" <?php echo ($user_data['acc_type'] == 'Regional Director') ? 'selected' : '' ?>>Regional Director (Approver)</option>
                                        <option value="Cashier" <?php echo ($user_data['acc_type'] == 'Cashier') ? 'selected' : '' ?>>Cashier</option>
                                    </select>
                                </div>
                            <?php } ?>
                            <input type="hidden" id="removedProfilePicture" name="removedProfilePicture" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update" class="btn btn-warning">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php echo $footer; ?>

    <script>
        document.getElementById('editUserForm1<?php echo $user_data['user_id']; ?>').addEventListener('submit', function(event) {
            var password = document.getElementById('password2').value;
            var passwordError = document.getElementById('password-error2');

            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (!passwordRegex.test(password)) {

                passwordError.style.display = 'block';

                event.preventDefault();
            } else {

                passwordError.style.display = 'none';
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function generateSpinNo() {
                const part1 = Math.floor(10 + Math.random() * 90);
                const part2 = Math.floor(1000 + Math.random() * 9000);
                const part3 = Math.floor(100 + Math.random() * 900);
                const part4 = String(Math.floor(Math.random() * 12 + 1)).padStart(2, '0');
                const part5 = String(Math.floor(Math.random() * 31 + 1)).padStart(2, '0');
                const part6 = Math.floor(1 + Math.random() * 9);
                return `${part1}-${part2}-${part3}-${part4}-${part5}-${part6}`;
            }

            function checkSpinNoUniqueness(spinNo) {
                return fetch('check_spin_no.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'spin_no=' + encodeURIComponent(spinNo)
                    })
                    .then(response => response.json())
                    .then(data => data.isUnique);
            }

            async function generateUniqueSpinNo() {
                let spinNo;
                let isUnique = false;

                while (!isUnique) {
                    spinNo = generateSpinNo();
                    isUnique = await checkSpinNoUniqueness(spinNo);
                }

                return spinNo;
            }

            document.getElementById('generate_spin').addEventListener('click', async function() {
                const spinInputField = document.getElementById('spin_no');
                spinInputField.value = 'Generating...';
                const uniqueSpinNo = await generateUniqueSpinNo();
                spinInputField.value = uniqueSpinNo;
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function generateSpinNo() {
                const randomPart1 = Math.floor(10 + Math.random() * 90);
                const randomPart2 = Math.floor(1000 + Math.random() * 9000);
                const randomPart3 = Math.floor(100 + Math.random() * 900);
                const randomMonth = String(Math.floor(Math.random() * 12 + 1)).padStart(2, '0');
                const randomDay = String(Math.floor(Math.random() * 31 + 1)).padStart(2, '0');
                const randomDigit = Math.floor(1 + Math.random() * 9);

                return `${randomPart1}-${randomPart2}-${randomPart3}-${randomMonth}-${randomDay}-${randomDigit}`;
            }

            function checkSpinNoUniqueness(spinNo) {
                return fetch('check_spin_no.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'spin_no=' + encodeURIComponent(spinNo)
                    })
                    .then(response => response.json())
                    .then(data => data.isUnique);
            }

            async function generateUniqueSpinNo() {
                let spinNo;
                let isUnique = false;

                while (!isUnique) {
                    spinNo = generateSpinNo();
                    isUnique = await checkSpinNoUniqueness(spinNo);
                }

                return spinNo;
            }

            document.querySelectorAll('.generate-spin').forEach(button => {
                button.addEventListener('click', async function() {
                    const targetId = this.getAttribute('data-target');
                    const spinInputField = document.getElementById(`spin_no_other_${targetId}`);
                    const uniqueSpinNo = await generateUniqueSpinNo();
                    spinInputField.value = uniqueSpinNo;
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("DOM fully loaded");
            console.log("Status from URL:", getUrlParameter('status'));
        });
    </script>

    <script defer src="assets/js/autoUpdate.js"></script>
    <script src="assets/DataTables/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script defer src="assets/DataTables/datatables.min.js"></script>
    <script src="assets/DataTables/pdfmake.min.js"></script>
    <script src="assets/DataTables/vfs_fonts.js"></script>
    <script>
        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }

        function updateUrlParameter(param, paramVal) {
            var url = new URL(window.location.href);
            url.searchParams.set(param, paramVal);
            window.history.pushState({}, '', url.href);
        }

        $(document).ready(function() {
            var table = $("#example").DataTable({
                responsive: true,
                info: true,
                lengthMenu: [25, 50, 100, 250, 500],
                pageLength: 25,
                dom: "<'row pt-2'<'col-sm-12 col-md-4'B><'col-sm-12 col-md-4 d-flex justify-content-center'l><'col-sm-12 col-md-4'f>>" +
                    "<'row mt-2'<'col-sm-12'tr>>" +
                    "<'row mt-2'<'col-sm-12 d-flex justify-content-center align-items-center'i>>" +
                    "<'row mt-2'<'col-sm-12 d-flex justify-content-center align-items-center'p>>",
                buttons: [{
                        extend: "print",
                        className: "btn btn-info btn-sm",
                        exportOptions: {
                            columns: ":not(:last-child)",
                            stripHtml: false,
                        },
                    },
                    {
                        extend: "excelHtml5",
                        className: "btn btn-success btn-sm",
                        exportOptions: {
                            columns: ":not(:last-child)",
                            format: {
                                body: function(data, row, column, node) {
                                    data = data
                                        .replace(/<[^>]*>/gi, "")
                                        .replace(/<\/?span>/gi, "")
                                        .replace(/<[^>]*>/gi, "")
                                        .replace(/<\/?strong>/gi, "")
                                        .replace(/\n/g, "")
                                        .split("\n")
                                        .map((line) => line.trim())
                                        .join("\n");
                                    return data;
                                },
                            },
                        },
                    },
                    {
                        extend: "pdfHtml5",
                        className: "btn btn-danger btn-sm",
                        exportOptions: {
                            columns: ":not(:last-child)",
                        },
                        customize: function(doc) {
                            var body = doc.content[1].table.body;
                            for (var i = 0; i < body.length; i++) {
                                for (var j = 0; j < body[i].length; j++) {
                                    var cell = body[i][j];
                                    if (typeof cell === "string") {
                                        cell = cell.replace(/\n/g, "\n");
                                        body[i][j] = {
                                            text: cell,
                                            margin: [5, 5, 5, 5]
                                        };
                                    }
                                }

                                if (i % 2 === 1) {
                                    for (var j = 0; j < body[i].length; j++) {
                                        body[i][j].fillColor = "#f2f2f2";
                                    }
                                }
                            }
                            doc.content[1].layout = {
                                hLineWidth: function(i, node) {
                                    return 0.5;
                                },
                                vLineWidth: function(i, node) {
                                    return 0.5;
                                },
                                hLineColor: function(i, node) {
                                    return "#dee2e6";
                                },
                                vLineColor: function(i, node) {
                                    return "#dee2e6";
                                },
                            };
                        },
                    },
                ],
                initComplete: function() {
                    var btns = $(".dt-button");
                    btns.removeClass("dt-button");

                    var filterBtnHtml = `
            <div class="btn-group ms-2">
            <a type="button" class="dropdown-toggle text-dark text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                Filter
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-3 mt-2" style="min-width: 300px;">
                <div class="mb-2">
                <label for="filter-year">Year</label>
                <select id="filter-year" class="form-select form-select-sm mt-1">
                    <option value="">-</option>
                </select>
                </div>
                <div class="mb-2">
                <label for="filter-sector">Sector</label>
                <select id="filter-sector" class="form-select form-select-sm mt-1">
                    <option value="">-</option>
                </select>
                </div>
                <div class="mb-2">
                <label for="filter-category">Category</label>
                <select id="filter-category" class="form-select form-select-sm mt-1">
                    <option value="">-</option>
                </select>
                </div>
                <div class="mb-2">
                <label for="filter-province">Province</label>
                <select id="filter-province" class="form-select form-select-sm mt-1">
                    <option value="">-</option>
                </select>
                </div>
                <div class="mt-3">
                <button id="reset-filters" class="btn btn-sm btn-danger w-100">Reset Filters</button>
                </div>
            </ul>
            </div>`;

                    $(filterBtnHtml).appendTo(".dt-search");

                    $.ajax({
                        url: "nohardcode.php",
                        method: "GET",
                        dataType: "json",
                        success: function(data) {
                            populateDropdown("#filter-year", data[0]);
                            populateDropdown("#filter-sector", data[1]);
                            populateDropdown("#filter-category", data[2]);
                            populateDropdown("#filter-province", data[3]);
                            populateDropdown("#filter-status", data[4]);
                        },
                    });

                    function populateDropdown(selector, options) {
                        var dropdown = $(selector);
                        options.forEach(function(option) {
                            var value = Object.values(option)[0];
                            dropdown.append(
                                '<option value="' + value + '">' + value + "</option>"
                            );
                        });
                    }

                    $(
                        "#filter-year, #filter-sector, #filter-category, #filter-province, #filter-status"
                    ).on("change", function() {
                        applyFilters();
                    });

                    $("#reset-filters").on("click", function() {
                        $(
                            "#filter-year, #filter-sector, #filter-category, #filter-province, #filter-status"
                        ).val("");

                        applyFilters();
                    });

                    var statusFilter = getUrlParameter('status');
                    if (statusFilter) {
                        $('#filter-status').val(statusFilter);
                        applyFilters();

                        updateUrlParameter('status', '');
                    }

                    function applyFilters() {
                        var year = $("#filter-year").val();
                        var sector = $("#filter-sector").val();
                        var category = $("#filter-category").val();
                        var province = $("#filter-province").val();
                        var status = $("#filter-status").val();

                        table.columns().every(function(index) {
                            var column = this;
                            var columnTitle = $(column.header()).text().trim().toLowerCase();

                            if (columnTitle === "project detail") {
                                column.search(province).draw();
                            } else {
                                switch (columnTitle) {
                                    case "sector":
                                        column.search(sector).draw();
                                        break;
                                    case "category":
                                        column.search(category).draw();
                                        break;
                                    case "year":
                                        column.search(year).draw();
                                        break;
                                    case "status":
                                        column.search(status).draw();
                                        break;
                                    default:
                                        column.search("").draw();
                                        break;
                                }
                            }
                        });
                    }
                },
            });
        });
    </script>

</body>

</html>