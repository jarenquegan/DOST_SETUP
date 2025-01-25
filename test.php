<?php
date_default_timezone_set('Asia/Manila');
require_once 'session.php';
require_once 'class.php';
$db = new db_class();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Records</title>
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

    <link rel="stylesheet" href="https:
    <script src=" https:
        <script src="https:

</head>

<body class=" bg-light" id="page-top">
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
                        <a class="nav-link" href="projects.php">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="refund.php">Refunds</a>
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

    <div class="container-fluid mt-3 bg-dark">
        <h3 class="text-center" style="color: white; padding: 2px;">PAYMENT RECORDS</h3>
    </div>

    <div class="container-fluid mt-3">
        <div class="card shadow mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="refundDataTable" style="width:100%" class="display compact table table-bordered table-striped table-condensed small">
                        <thead>
                            <tr class="fw-bold">
                                <th style="text-align: left;" class="content-fit text-center"></th>
                                <th style="text-align: left;">Project Spin No.</th>
                                <td style="text-align: left;">Payee</th>
                                <td style="text-align: left;">Amount</th>
                                <td style="text-align: left;">OR Number</th>
                                <td style="text-align: left;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tbl_refund = $db->conn->query("SELECT * FROM `refund` INNER JOIN `project` ON refund.project_id=project.project_id");
                            $i = 1;
                            while ($fetch = $tbl_refund->fetch_array()) {
                            ?>
                                <tr>
                                    <td style="text-align: left;" class="content-fit text-center"><?php echo $i++; ?></td>
                                    <td style="text-align: left;"><?php echo $fetch['spin_no'] ?></td>
                                    <td style="text-align: left;"><?php echo $fetch['payee'] ?></td>
                                    <td style="text-align: left;"><?php echo "&#8369; " . number_format($fetch['pay_amount'], 2) ?></td>
                                    <td style="text-align: left;"><?php echo $fetch['or_number'] ?></td>
                                    <?php
                                    date_default_timezone_set('Asia/Manila');
                                    $date_created = $fetch['date_created'];

                                    $date = new DateTime($date_created);
                                    $formatted_date = $date->format('Y-m-d H:i:s');
                                    ?>
                                    <td style="text-align: left;"><?php echo $formatted_date; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- HTML for Modal -->
    <div class="modal fade" id="addRefund" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="save_refund.php">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Refund Form</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3" id="formField">
                            <div class="col-md-12">
                                <label for="spin_no" class="form-label">Spin No.</label>
                                <input type="text" class="form-control" id="spin_no" required>
                                <input type="hidden" name="project_id" id="project_id">
                            </div>

                            <div class="col-md-12">
                                <label for="payee" class="form-label">Payee</label>
                                <input type="text" id="payee" name="payee" class="form-control" readonly />
                                <input type="hidden" id="balance" name="balance" />
                                <input type="hidden" id="payable" name="payable" />
                                <input type="hidden" id="monthly" name="monthly" />
                            </div>
                            <div class="col-md-12">
                                <label for="refund" class="form-label">Amount</label>
                                <input type="text" class="form-control" name="refund" id="refund" required onkeyup="this.value=this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')" />
                            </div>
                            <div class="col-md-12">
                                <label for="or_number" class="form-label">OR Number</label>
                                <input type="text" class="form-control" name="or_number" id="or_number" required />
                            </div>
                            <hr id="line">
                            <div class="row text-center" id="calcTable">
                                <div class="col-md-4">
                                    <span>Total Payable Amount</span><br>
                                    <span id="payableAmount"><?= "&#8369;" ?> 0.00</span>
                                </div>
                                <div class="col-md-4">
                                    <span>Monthly Payable Amount</span><br>
                                    <span id="monthlyAmount"><?= "&#8369;" ?> 0.00</span>
                                </div>
                                <div class="col-md-4">
                                    <span>Balance</span><br>
                                    <span id="balanceAmount"><?= "&#8369;" ?> 0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="save" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('#spin_no').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: 'search_references.php',
                        dataType: 'json',
                        data: {
                            term: request.term
                        },
                        success: function(data) {

                            response(data);
                        }
                    });
                },
                select: function(event, ui) {

                    $('#project_id').val(ui.item.value);

                    $.ajax({
                        url: "get_field.php",
                        type: "GET",
                        data: {
                            project_id: ui.item.value
                        },
                        success: function(data) {
                            var fields = JSON.parse(data);
                            $('#payee').val(fields.payee);
                            $('#balance').val(fields.balance);
                            $('#payable').val(fields.payable);
                            $('#monthly').val(fields.monthly);
                            $('#monthlyAmount').text('₱ ' + fields.monthlyAmount);
                            $('#balanceAmount').text('₱ ' + fields.balanceAmount);
                            $('#payableAmount').text('₱ ' + fields.payableAmount);
                        }
                    });
                }
            });
        });
    </script>

    <a class="scroll-to-top rounded-circle" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div id="container-floating">
        <div id="floating-button">
            <button type="button" class="custom-btn" data-bs-toggle="modal" data-bs-target="#addRefund" id="   "
                <?php echo ($account_type != 'Cashier') ? 'disabled' : ''; ?>>
                <p class="plus">
                    <i class="fa-solid fa-plus"></i>
                </p>
            </button>
        </div>
    </div>

    <script src="assets/js/sb-admin-2.js"></script>
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

    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-4 mb-0 text-body-secondary">&copy; DOST <?php echo date("Y") ?></p>

            <a href="index.php" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none navbar-brand logo">
                <h5>D<span class="logo-color">O</span>ST</h5>
            </a>

            <ul class="nav col-md-4 justify-content-end">
                <li class="nav-item"><a href="index.php" class="nav-link px-2 text-body-secondary">Home</a></li>
                <li class="nav-item"><a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" class="nav-link px-2 text-body-secondary">Logout</a></li>
            </ul>
        </footer>
    </div>

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
    <script defer src="assets/DataTables/datatables.min.js"></script>
    <script src="assets/DataTables/pdfmake.min.js"></script>
    <script src="assets/DataTables/vfs_fonts.js"></script>
    <script defer src="assets/js/functions.js"></script>
    </body>

</html>