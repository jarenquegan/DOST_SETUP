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
    <title>Users</title>
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
                        <a class="nav-link active" aria-current="page" href="users.php">Users</a>
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

    <div class="container-fluid mt-3 bg-custom" id="page-top">
        <h3 class="text-center" style="color: white; padding: 2px;">LIST OF USERS</h3>
    </div>

    <div class="container-fluid mt-3">
        <div class="card shadow mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="userDataTable" style="width:100%" class="display compact table table-bordered table-striped table-condensed small">
                        <thead>
                            <tr class="fw-bold">
                                <th style="text-align: left;" class="content-fit text-center"></th>
                                <td style="text-align: left;" class="content-fit text-center">DP</th>
                                <td style="text-align: left;">Username</th>
                                <td style="text-align: left;">Name</th>
                                <td style="text-align: left;">Account Type</th>
                                <td style="text-align: left;">Password</th>
                                <th style="text-align: left;" class="content-fit">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tbl_user = $db->display_user();
                            $i = 1;
                            while ($fetch = $tbl_user->fetch_array()) {
                            ?>
                                <tr>
                                    <td style="text-align: left;" class="content-fit text-center"><?php echo $i++; ?></td>
                                    <td style="text-align: center;" class="content-fit">
                                        <img class="img-profile rounded-circle" src="assets/images/<?php echo $fetch['user_pic'] ?>" alt="<?php echo $fetch['username'] ?>" style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td style="text-align: left;"><?php echo $fetch['username'] ?></td>
                                    <td style="text-align: left;"><?php echo $fetch['firstname'] . " " . $fetch['lastname'] ?></td>
                                    <td style="text-align: left;" class="content-min"><?php echo $fetch['acc_type'] ?></td>
                                    <td style="text-align: left;"><?php echo $db->hide_pass($fetch['password']) ?></td>
                                    <td style="text-align: left;" class="content-fit">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                                Action
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php if (($account_type == 'Administrator') && !($fetch['user_id'] == $account_id)) { ?>
                                                    <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $fetch['user_id'] ?>">Edit</a></li>
                                                    <?php if ($fetch['user_id'] == $_SESSION['user_id']) { ?>
                                                        <li><a class="dropdown-item small" href="#" disabled="disabled">Cannot Delete</a></li>
                                                    <?php } else { ?>
                                                        <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $fetch['user_id'] ?>">Delete</a></li>
                                                    <?php } ?>
                                                <?php } elseif ($fetch['user_id'] == $account_id) { ?>
                                                    <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#editUser<?php echo $user_data['user_id'] ?>">Edit</a></li>
                                                    <?php if ($fetch['user_id'] == $_SESSION['user_id']) { ?>
                                                        <li><a class="dropdown-item small" href="#" disabled="disabled">Cannot Delete</a></li>
                                                    <?php } else { ?>
                                                        <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $fetch['user_id'] ?>">Delete</a></li>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <li><a class="dropdown-item small" href="#" disabled="disabled">No Access</a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editUserModal<?php echo $fetch['user_id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <form method="POST" id="editUserModalForm<?php echo $fetch['user_id'] ?>" action="updateUser.php" enctype="multipart/form-data">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title text-white">Edit User</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <input type="hidden" value="<?php echo $fetch['user_id'] ?>" name="user_id_modal1" />
                                                        <div class="col-12">
                                                            <div class="d-flex justify-content-center">
                                                                <img id="previewImage_modal<?php echo $fetch['user_id'] ?>" src="assets/images/<?php echo $fetch['user_pic']; ?>" class="rounded-circle img-thumbnail" alt="Profile Preview" style="width: 150px; height: 150px; object-fit: cover;">
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="d-flex justify-content-center">
                                                                <strong>
                                                                    <h4 id="userFullName_modal<?php echo $fetch['user_id'] ?>" class="form-label d-flex justify-content-center"><?php echo $fetch['firstname'] . " " . $fetch['lastname']; ?></h4>
                                                                </strong>
                                                            </div>
                                                        </div>
                                                        <?php if ($fetch['user_id'] != $account_id) { ?>
                                                            <input type="hidden" name="user_pic_modal1" value="<?php echo $fetch['user_pic']; ?>">
                                                        <?php } ?>
                                                        <div class="col-md-6">
                                                            <label for="username_modal<?php echo $fetch['user_id'] ?>" class="form-label">Username</label>
                                                            <input type="text" class="form-control" id="username_modal<?php echo $fetch['user_id'] ?>" name="username_modal1" value="<?php echo $fetch['username'] ?>" required />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="firstname_modal<?php echo $fetch['user_id'] ?>" class="form-label">First Name</label>
                                                            <input type="text" class="form-control" id="firstname_modal<?php echo $fetch['user_id'] ?>" name="firstname_modal1" value="<?php echo $fetch['firstname'] ?>" required />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="lastname_modal<?php echo $fetch['user_id'] ?>" class="form-label">Last Name</label>
                                                            <input type="text" class="form-control" id="lastname_modal<?php echo $fetch['user_id'] ?>" name="lastname_modal1" value="<?php echo $fetch['lastname'] ?>" required />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="password_modal<?php echo $fetch['user_id'] ?>" class="form-label">Password</label>
                                                            <input type="password" class="form-control" id="password_modal<?php echo $fetch['user_id'] ?>" name="password_modal1" value="<?php echo $fetch['password'] ?>" required />
                                                        </div>
                                                        <div class="col-md-12" id="password-errorz<?php echo $fetch['user_id'] ?>" style="color:red; display:none; text-align:center;">
                                                            Error: The password must be at least 8 characters long, include uppercase and lowercase letters, a number, and a symbol.
                                                        </div>
                                                        <?php if ($account_type != 'Administrator') { ?>
                                                            <input type="hidden" name="acc_type_modal1" value="<?php echo htmlspecialchars($fetch['acc_type']); ?>">
                                                        <?php } ?>
                                                        <?php if ($account_type == 'Administrator') { ?>
                                                            <div class="col-md-12">
                                                                <label for="acc_type_modal<?php echo $fetch['user_id'] ?>" class="form-label">Account Type</label>
                                                                <select class="form-select" name="acc_type_modal1" required>
                                                                    <option value="Administrator" <?php echo ($fetch['acc_type'] == 'Administrator') ? 'selected' : '' ?>>Administrator</option>
                                                                    <option value="Setup Officer" <?php echo ($fetch['acc_type'] == 'Setup Officer') ? 'selected' : '' ?>>Setup Officer</option>
                                                                    <option value="Regional Project Management Office (RPMO)" <?php echo ($fetch['acc_type'] == 'Regional Project Management Office (RPMO)') ? 'selected' : '' ?>>Regional Project Management Office (RPMO) (Checker)</option>
                                                                    <option value="Regional Director" <?php echo ($fetch['acc_type'] == 'Regional Director') ? 'selected' : '' ?>>Regional Director (Approver)</option>
                                                                    <option value="Cashier" <?php echo ($fetch['acc_type'] == 'Cashier') ? 'selected' : '' ?>>Cashier</option>
                                                                </select>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="update_modal1" class="btn btn-warning">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const userId = <?php echo json_encode($fetch['user_id']); ?>;
                                        const form = document.getElementById(`editUserModalForm${userId}`);
                                        const passwordInput = document.getElementById(`password_modal${userId}`);
                                        const passwordError = document.getElementById(`password-errorz${userId}`);
                                        const firstNameInput = document.getElementById(`firstname_modal${userId}`);
                                        const lastNameInput = document.getElementById(`lastname_modal${userId}`);
                                        const fullNameDisplay = document.getElementById(`userFullName_modal${userId}`);

                                        form.addEventListener('submit', function(event) {
                                            const password = passwordInput.value;
                                            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

                                            if (!passwordRegex.test(password)) {
                                                passwordError.style.display = 'block';
                                                event.preventDefault();
                                            } else {
                                                passwordError.style.display = 'none';
                                            }
                                        });

                                        function updateFullName() {
                                            const firstName = firstNameInput.value.trim();
                                            const lastName = lastNameInput.value.trim();
                                            const fullName = `${firstName} ${lastName}`.trim();
                                            fullNameDisplay.textContent = fullName || `<?php echo $fetch['firstname'] . " " . $fetch['lastname']; ?>`;
                                        }

                                        firstNameInput.addEventListener('input', updateFullName);
                                        lastNameInput.addEventListener('input', updateFullName);
                                    });
                                </script>

                                <div class="modal fade" id="deleteModal<?php echo $fetch['user_id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title text-white">System Information</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">Are you sure you want to delete this record?</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                                <a class="btn btn-danger" href="deleteUser.php?user_id=<?php echo $fetch['user_id'] ?>">Delete</a>
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

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="addUserForm" method="POST" action="addUser.php">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Add User</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" required />
                            </div>
                            <div class="col-md-6">
                                <label for="firstname" class="form-label">Firstname</label>
                                <input type="text" class="form-control" name="firstname" required />
                            </div>
                            <div class="col-md-6">
                                <label for="lastname" class="form-label">Lastname</label>
                                <input type="text" class="form-control" name="lastname" required />
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password1" name="password" required />
                            </div>
                            <div class="col-md-12" id="password-error1" style="color:red; display:none; text-align:center;">
                                Error: The password must be at least 8 characters long, include uppercase and lowercase letters, a number, and a symbol.
                            </div>
                            <div class="col-md-12">
                                <label for="acc_type" class="form-label">Account Type</label>
                                <select class="form-select" name="acc_type" required>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Setup Officer">Setup Officer</option>
                                    <option value="Regional Project Management Office (RPMO)">Regional Project Management Office (RPMO) (Checker)</option>
                                    <option value="Regional Director">Regional Director (Approver)</option>
                                    <option value="Cashier">Cashier</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="confirm" class="btn btn-primary">Confirm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('addUserForm').addEventListener('submit', function(event) {
            var password = document.getElementById('password1').value;
            var passwordError = document.getElementById('password-error1');

            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (!passwordRegex.test(password)) {

                passwordError.style.display = 'block';

                event.preventDefault();
            } else {

                passwordError.style.display = 'none';
            }
        });
    </script>

    <a class="scroll-to-top rounded-circle" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div id="container-floating">
        <div id="floating-button">
            <button type="button" class="custom-btn" data-bs-toggle="modal" data-bs-target="#addModal" id="add"
                <?php echo ($account_type != 'Administrator') ? 'disabled' : ''; ?>>
                <p class="plus">
                    <i class="fa-solid fa-plus"></i>
                </p>
            </button>
        </div>
    </div>

    <script src="js/jquery.easing.js"></script>
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

    <script defer src="assets/js/autoUpdate.js"></script>
    <script src="assets/DataTables/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script defer src="assets/DataTables/datatables.min.js"></script>
    <script src="assets/DataTables/pdfmake.min.js"></script>
    <script src="assets/DataTables/vfs_fonts.js"></script>
    <script defer src="assets/js/functions.js"></script>
</body>

</html>