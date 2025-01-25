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
    <title>Home</title>
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
    <script src="assets/js/Chart.min.js"></script>
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
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="projects.php">Projects</a>
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
        <h3 class="text-center" style="color: white; padding: 2px;">SETUP DASHBOARD</h3>
    </div>

    <div class="container-fluid mt-3">
        <div class="row row-cols-1 row-cols-md-3 g-3 small">
            <div class="col">
                <div class="card border-0 border-start border-indigo border-3 shadow h-100 custom-card-height">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-sm small fw-semibold text-indigo text-uppercase mb-1">
                                    Project List</div>
                                <div class="h1 mb-0 fw-semibold">
                                    <?php
                                    $join_query = "
                                        SELECT project.*, beneficiary.*
                                        FROM project
                                        JOIN beneficiary ON project.beneficiary_id = beneficiary.beneficiary_id
                                    ";

                                    $tbl_project = $db->conn->query($join_query);

                                    echo $tbl_project->num_rows > 0 ? $tbl_project->num_rows : "0";
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fs-4 text-body-tertiary fa-solid fa-briefcase"></i>
                                <i class=""></i>
                            </div>
                            <div class="mb-0">
                                <?php
                                $beneficiary_query = "
                                    SELECT beneficiary.province, COUNT(project.project_id) AS total_projects
                                    FROM project
                                    JOIN beneficiary ON project.beneficiary_id = beneficiary.beneficiary_id
                                    GROUP BY beneficiary.province
                                ";
                                $province_result = $db->conn->query($beneficiary_query);

                                if ($province_result->num_rows > 0) {

                                    while ($row = $province_result->fetch_assoc()) {
                                        echo $row['province'] . " - Total Projects: " . $row['total_projects'] . "<br>";
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small stretched-link text-decoration-none text-indigo" href="projects.php">See Project List</a>
                        <div class="small">
                            <i class="fa fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 border-start border-pink border-3 shadow h-100 custom-card-height">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-sm small fw-semibold text-pink text-uppercase mb-1">
                                    Refunds</div>
                                <div class="h1 mb-0 fw-semibold">
                                    <?php
                                    $refund = $db->conn->query("SELECT SUM(pay_amount) as total_refunded FROM `refund`") or die($db->conn->error);

                                    $result = $refund->fetch_assoc();

                                    $totalRefunded = $result['total_refunded'] ?? 0;

                                    echo "&#8369; " . number_format($totalRefunded, 2);
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fs-4 text-body-tertiary fa-solid fa-peso-sign"></i>
                            </div>
                            <div class="mb-0">
                                Assisted Amount:
                                <?php
                                $amount = $db->conn->query("SELECT SUM(amount) as total_amount FROM `project`") or die($db->conn->error);

                                $result = $amount->fetch_assoc();

                                $totalAmount = $result['total_amount'] ?? 0;

                                echo "&#8369; " . number_format($totalAmount, 2);
                                ?>
                            </div>
                            <div class="mb-0">
                                Amount to be Refunded:
                                <?= "&#8369; " . number_format(($totalAmount - $totalRefunded), 2); ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small stretched-link text-decoration-none text-pink" href="refund.php">See Refund Records</a>
                        <div class="small">
                            <i class="fa fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 border-start border-orange border-3 shadow h-100 custom-card-height">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-sm small fw-semibold text-orange text-uppercase mb-1">Beneficiaries
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h1 mb-0 mr-3 fw-semibold">
                                            <?php
                                            $tbl_beneficiary = $db->conn->query("SELECT * FROM `beneficiary`");
                                            echo $tbl_beneficiary->num_rows > 0 ? $tbl_beneficiary->num_rows : "0";
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fs-4 text-body-tertiary fa-solid fa-users"></i>
                            </div>
                            <div class="mb-0">
                                <?php
                                $beneficiary_query = "
                                    SELECT province, COUNT(*) AS total_beneficiaries
                                    FROM beneficiary
                                    GROUP BY province
                                ";
                                $beneficiary_result = $db->conn->query($beneficiary_query);

                                if ($beneficiary_result->num_rows > 0) {
                                    while ($row = $beneficiary_result->fetch_assoc()) {
                                        echo $row['province'] . " - Total Beneficiaries: " . $row['total_beneficiaries'] . "<br>";
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small stretched-link text-decoration-none text-orange" href="beneficiary.php">See Beneficiaries</a>
                        <div class="small">
                            <i class="fa fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 border-start border-danger border-3 shadow h-100 custom-card-height">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-sm small fw-semibold text-danger text-uppercase mb-1">
                                    Denied Projects</div>
                                <div class="h1 mb-0 fw-semibold">
                                    <?php
                                    $tbl_project = $db->conn->query("SELECT * FROM `project` WHERE `status`='4'");
                                    echo $tbl_project->num_rows > 0 ? $tbl_project->num_rows : "0";
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fs-4 text-body-tertiary fa-solid fa-briefcase"></i>
                            </div>
                            <div class="mb-0">
                                <?php
                                $beneficiary_query = "
                                    SELECT beneficiary.province, COUNT(project.project_id) AS total_projects
                                    FROM project
                                    JOIN beneficiary ON project.beneficiary_id = beneficiary.beneficiary_id
                                    WHERE project.status = '4'
                                    GROUP BY beneficiary.province
                                ";

                                $province_result = $db->conn->query($beneficiary_query);

                                if ($province_result->num_rows > 0) {
                                    while ($row = $province_result->fetch_assoc()) {
                                        echo $row['province'] . " - Total Projects: " . $row['total_projects'] . "<br>";
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small stretched-link text-decoration-none text-danger" href="projects-denied.php">See Project List</a>
                        <div class="small">
                            <i class="fa fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 border-start border-warning border-3 shadow h-100 custom-card-height">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-sm small fw-semibold text-warning text-uppercase mb-1">
                                    Request</div>
                                <div class="h1 mb-0 fw-semibold">
                                    <?php
                                    $tbl_project = $db->conn->query("SELECT * FROM `project` WHERE `status`='0'");
                                    echo $tbl_project->num_rows > 0 ? $tbl_project->num_rows : "0";
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fs-4 text-body-tertiary fa-solid fa-briefcase"></i>
                            </div>
                            <div class="mb-0">
                                <?php
                                $beneficiary_query = "
                                    SELECT beneficiary.province, COUNT(project.project_id) AS total_projects
                                    FROM project
                                    JOIN beneficiary ON project.beneficiary_id = beneficiary.beneficiary_id
                                    WHERE project.status = '0'
                                    GROUP BY beneficiary.province
                                ";

                                $province_result = $db->conn->query($beneficiary_query);

                                if ($province_result->num_rows > 0) {
                                    while ($row = $province_result->fetch_assoc()) {
                                        echo $row['province'] . " - Total Projects: " . $row['total_projects'] . "<br>";
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small stretched-link text-decoration-none text-warning" href="projects-request.php">See Project List</a>
                        <div class="small">
                            <i class="fa fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 border-start border-purple border-3 shadow h-100 custom-card-height">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-sm small fw-semibold text-purple text-uppercase mb-1">
                                    Checked</div>
                                <div class="h1 mb-0 fw-semibold">
                                    <?php
                                    $tbl_project = $db->conn->query("SELECT * FROM `project` WHERE `status`='5'");
                                    echo $tbl_project->num_rows > 0 ? $tbl_project->num_rows : "0";
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fs-4 text-body-tertiary fa-solid fa-briefcase"></i>
                            </div>
                            <div class="mb-0">
                                <?php
                                $beneficiary_query = "
                                    SELECT beneficiary.province, COUNT(project.project_id) AS total_projects
                                    FROM project
                                    JOIN beneficiary ON project.beneficiary_id = beneficiary.beneficiary_id
                                    WHERE project.status = '5'
                                    GROUP BY beneficiary.province
                                ";

                                $province_result = $db->conn->query($beneficiary_query);

                                if ($province_result->num_rows > 0) {
                                    while ($row = $province_result->fetch_assoc()) {
                                        echo $row['province'] . " - Total Projects: " . $row['total_projects'] . "<br>";
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small stretched-link text-decoration-none text-purple" href="projects-checked.php">See Project List</a>
                        <div class="small">
                            <i class="fa fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 border-start border-info border-3 shadow h-100 custom-card-height">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-sm small fw-semibold text-info text-uppercase mb-1">
                                    Approved Projects</div>
                                <div class="h1 mb-0 fw-semibold">
                                    <?php
                                    $tbl_project = $db->conn->query("SELECT * FROM `project` WHERE `status`='1'");
                                    echo $tbl_project->num_rows > 0 ? $tbl_project->num_rows : "0";
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fs-4 text-body-tertiary fa-solid fa-briefcase"></i>
                            </div>
                            <div class="mb-0">
                                <?php
                                $beneficiary_query = "
                                    SELECT beneficiary.province, COUNT(project.project_id) AS total_projects
                                    FROM project
                                    JOIN beneficiary ON project.beneficiary_id = beneficiary.beneficiary_id
                                    WHERE project.status = '1'
                                    GROUP BY beneficiary.province
                                ";

                                $province_result = $db->conn->query($beneficiary_query);

                                if ($province_result->num_rows > 0) {
                                    while ($row = $province_result->fetch_assoc()) {
                                        echo $row['province'] . " - Total Projects: " . $row['total_projects'] . "<br>";
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small stretched-link text-decoration-none text-info" href="projects-approved.php">See Project List</a>
                        <div class="small">
                            <i class="fa fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 border-start border-primary border-3 shadow h-100 custom-card-height">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-sm small fw-semibold text-primary text-uppercase mb-1">
                                    Active Projects</div>
                                <div class="h1 mb-0 fw-semibold">
                                    <?php
                                    $tbl_project = $db->conn->query("SELECT * FROM `project` WHERE `status`='2'");
                                    echo $tbl_project->num_rows > 0 ? $tbl_project->num_rows : "0";
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fs-4 text-body-tertiary fa-solid fa-briefcase"></i>
                            </div>
                            <div class="mb-0">
                                <?php
                                $beneficiary_query = "
                                    SELECT beneficiary.province, COUNT(project.project_id) AS total_projects
                                    FROM project
                                    JOIN beneficiary ON project.beneficiary_id = beneficiary.beneficiary_id
                                    WHERE project.status = '2'
                                    GROUP BY beneficiary.province
                                ";

                                $province_result = $db->conn->query($beneficiary_query);

                                if ($province_result->num_rows > 0) {
                                    while ($row = $province_result->fetch_assoc()) {
                                        echo $row['province'] . " - Total Projects: " . $row['total_projects'] . "<br>";
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small stretched-link text-decoration-none text-primary" href="projects-active.php">See Project List</a>
                        <div class="small">
                            <i class="fa fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-0 border-start border-success border-3 shadow h-100 custom-card-height">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-sm small fw-semibold text-success text-uppercase mb-1">
                                    Matured Projects</div>
                                <div class="h1 mb-0 fw-semibold">
                                    <?php
                                    $tbl_project = $db->conn->query("SELECT * FROM `project` WHERE `status`='3'");
                                    echo $tbl_project->num_rows > 0 ? $tbl_project->num_rows : "0";
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fs-4 text-body-tertiary fa-solid fa-briefcase"></i>
                            </div>
                            <div class="mb-0">
                                <?php
                                $beneficiary_query = "
                                    SELECT beneficiary.province, COUNT(project.project_id) AS total_projects
                                    FROM project
                                    JOIN beneficiary ON project.beneficiary_id = beneficiary.beneficiary_id
                                    WHERE project.status = '3'
                                    GROUP BY beneficiary.province
                                ";

                                $province_result = $db->conn->query($beneficiary_query);

                                if ($province_result->num_rows > 0) {
                                    while ($row = $province_result->fetch_assoc()) {
                                        echo $row['province'] . " - Total Projects: " . $row['total_projects'] . "<br>";
                                    }
                                } else {
                                    echo "0 results";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small stretched-link text-decoration-none text-success" href="projects-matured.php">See Project List</a>
                        <div class="small">
                            <i class="fa fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/functions.js"></script>
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

    <div class="mt-3"></div>
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

    <script defer src="assets/js/autoUpdate.js"></script>
    <script src="assets/DataTables/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script defer src="assets/DataTables/datatables.min.js"></script>
    <script src="assets/DataTables/pdfmake.min.js"></script>
    <script src="assets/DataTables/vfs_fonts.js"></script>
    <script defer src="assets/js/functions.js"></script>
</body>

</html>