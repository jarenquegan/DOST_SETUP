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
    <title>Beneficiaries</title>
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
                        <a class="nav-link active" href="beneficiary.php">Beneficiaries</a>
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
        <h3 class="text-center" style="color: white; padding: 2px;">LIST OF BENEFICIARIES</h3>
    </div>

    <div class="container-fluid mt-3">
        <div class="card shadow mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="beneficiaryDataTable" style="width:100%" class="display compact table table-bordered table-striped table-condensed small">
                        <thead>
                            <tr class="fw-bold">
                                <th style="text-align: left;" class="content-fit text-center"></th>
                                <th style="text-align: left;">Firmname</th>
                                <th style="text-align: left;">Owner</th>
                                <th style="text-align: left;">TIN</th>
                                <th style="text-align: left;">Address</th>
                                <th style="text-align: left;">Tel No.</th>
                                <th style="text-align: left;">Contact No.</th>
                                <th style="text-align: left;">Province</th>
                                <th style="text-align: left;">Sector</th>
                                <th style="text-align: left;">Category</th>
                                <th style="text-align: left;">Email</th>
                                <th style="text-align: left;" class="content-fit">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tbl_beneficiary = $db->display_beneficiary();
                            $i = 1;
                            while ($fetch = $tbl_beneficiary->fetch_array()) {
                            ?>
                                <tr>
                                    <td style="text-align: left;" class="content-fit text-center"><?php echo $i++; ?></td>
                                    <td style="text-align: left;"><?php echo $fetch['firmname'] ?></td>
                                    <td style="text-align: left;">
                                        <?php echo $fetch['firstname'] . " " . substr($fetch['middlename'], 0, 1) . ". " . $fetch['lastname']; ?>
                                    </td>
                                    <td style="text-align: left;">
                                        <?php echo substr($fetch['tin'], 0, 3) . "-" . substr($fetch['tin'], 3, 3) . "-" . substr($fetch['tin'], 6, 3) . "-" . substr($fetch['tin'], 9); ?>
                                    </td>
                                    <td style="text-align: left;"><?php echo $fetch['address'] ?></td>
                                    <td style="text-align: left;">
                                        <?php echo "(02) " . substr($fetch['tel_no'], 0, 4) . " " . substr($fetch['tel_no'], 4, 4); ?>
                                    </td>
                                    <td style="text-align: left;">
                                        <?php echo substr($fetch['contact_no'], 0, 4) . " " . substr($fetch['contact_no'], 4, 3) . " " . substr($fetch['contact_no'], 7, 4); ?>
                                    </td>
                                    <td style="text-align: left;"><?php echo $fetch['province'] ?></td>
                                    <td style="text-align: left;"><?php echo $fetch['sector'] ?></td>
                                    <td style="text-align: left;"><?php echo $fetch['category'] ?></td>
                                    <td style="text-align: left;"><?php echo $fetch['email'] ?></td>

                                    <td style="text-align: left;">
                                        <?php
                                        $user_data = $db->user_account($_SESSION['user_id']);
                                        $account_type = $user_data['acc_type'];
                                        ?>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                                Action
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php if ($account_type == 'Administrator' || $account_type == 'Setup Officer') { ?>
                                                    <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#updatebeneficiary<?php echo $fetch['beneficiary_id'] ?>">Edit</a></li>
                                                    <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#deletebeneficiary<?php echo $fetch['beneficiary_id'] ?>">Delete</a></li>
                                                <?php } else { ?>
                                                    <li><a class="dropdown-item small" href="#" disabled>No Access</a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="updatebeneficiary<?php echo $fetch['beneficiary_id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <form method="POST" action="updateBeneficiary.php">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title text-white">Edit Beneficiary</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-12">
                                                            <label for="firmname" class="form-label">Firmname</label>
                                                            <input type="text" name="firmname" value="<?php echo $fetch['firmname'] ?>" class="form-control" required />
                                                            <input type="hidden" name="beneficiary_id" value="<?php echo $fetch['beneficiary_id'] ?>" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="firstname" class="form-label">Firstname</label>
                                                            <input type="text" name="firstname" value="<?php echo $fetch['firstname'] ?>" class="form-control" required />
                                                            <input type="hidden" name="beneficiary_id" value="<?php echo $fetch['beneficiary_id'] ?>" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="middlename" class="form-label">Middlename</label>
                                                            <input type="text" name="middlename" value="<?php echo $fetch['middlename'] ?>" class="form-control" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="lastname" class="form-label">Lastname</label>
                                                            <input type="text" name="lastname" value="<?php echo $fetch['lastname'] ?>" class="form-control" required />
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="tin" class="form-label">TIN</label>
                                                            <input type="text" name="tin" value="<?php echo $fetch['tin'] ?>" class="form-control" maxlength="12" minlength="12" required />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="address" class="form-label">Address</label>
                                                            <input type="text" name="address" value="<?php echo $fetch['address'] ?>" class="form-control" required />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="province" class="form-label">Province</label>
                                                            <input type="text" name="province" value="<?php echo $fetch['province'] ?>" class="form-control" required />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="tel_no" class="form-label">Tel No.</label>
                                                            <input type="tel" name="tel_no" value="<?php echo $fetch['tel_no'] ?>" class="form-control" placeholder="Eg. 12345678" maxlength="8" minlength="8" required />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="contact_no" class="form-label">Contact No.</label>
                                                            <input type="tel" name="contact_no" value="<?php echo $fetch['contact_no'] ?>" class="form-control" placeholder="Eg. 09260876816" maxlength="11" minlength="11" required />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="sector" class="form-label">Sector</label>
                                                            <select name="sector" class="form-select" required>
                                                                <option value="">Select Sector</option>
                                                                <option value="Food Processing" <?php echo ($fetch['sector'] == 'Food Processing') ? 'selected' : ''; ?>>Food Processing</option>
                                                                <option value="Horticulture/Agriculture" <?php echo ($fetch['sector'] == 'Horticulture/Agriculture') ? 'selected' : ''; ?>>Horticulture/Agriculture</option>
                                                                <option value="Aquamarine" <?php echo ($fetch['sector'] == 'Aquamarine') ? 'selected' : ''; ?>>Aquamarine</option>
                                                                <option value="Metals & Engineering" <?php echo ($fetch['sector'] == 'Metals & Engineering') ? 'selected' : ''; ?>>Metals & Engineering</option>
                                                                <option value="Furniture" <?php echo ($fetch['sector'] == 'Furniture') ? 'selected' : ''; ?>>Furniture</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="category" class="form-label">Category</label>
                                                            <select name="category" class="form-select" required>
                                                                <option value="">Select Category</option>
                                                                <option value="Micro" <?php echo ($fetch['category'] == 'Micro') ? 'selected' : ''; ?>>Micro</option>
                                                                <option value="Medium" <?php echo ($fetch['category'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                                                                <option value="Macro" <?php echo ($fetch['category'] == 'Macro') ? 'selected' : ''; ?>>Macro</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="email" name="email" value="<?php echo $fetch['email'] ?>" class="form-control" required maxlength="30" />
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

                                <div class="modal fade" id="deletebeneficiary<?php echo $fetch['beneficiary_id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title text-white">System Information</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">Are you sure you want to delete this record?</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                                <a class="btn btn-danger" href="deleteBeneficiary.php?beneficiary_id=<?php echo $fetch['beneficiary_id'] ?>">Delete</a>
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
            <form method="POST" action="save_beneficiary.php">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Add Beneficiary</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="firmname" class="form-label">Firmname</label>
                                <input type="text" name="firmname" class="form-control" required />
                            </div>
                            <div class="col-md-4">
                                <label for="firstname" class="form-label">Firstname</label>
                                <input type="text" name="firstname" class="form-control" required />
                            </div>
                            <div class="col-md-4">
                                <label for="middlename" class="form-label">Middlename</label>
                                <input type="text" name="middlename" class="form-control" />
                            </div>
                            <div class="col-md-4">
                                <label for="lastname" class="form-label">Lastname</label>
                                <input type="text" name="lastname" class="form-control" required />
                            </div>
                            <div class="col-md-12">
                                <label for="tin" class="form-label">TIN</label>
                                <input type="text" name="tin" class="form-control" placeholder="Eg. 1234567891234" maxlength="12" minlength="12" required />
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label for="province" class="form-label">Province</label>
                                <input type="text" name="province" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label for="tel_no" class="form-label">Tel No.</label>
                                <input type="tel" name="tel_no" class="form-control" placeholder="Eg. 12345678" maxlength="8" minlength="8" required />
                            </div>
                            <div class="col-md-6">
                                <label for="contact_no" class="form-label">Contact No.</label>
                                <input type="tel" name="contact_no" class="form-control" placeholder="Eg. 09260876816" maxlength="11" minlength="11" required />
                            </div>
                            <div class="col-md-6">
                                <label for="sector" class="form-label">Sector</label>
                                <select name="sector" class="form-select" required>
                                    <option value="">Select Sector</option>
                                    <option value="Food Processing">Food Processing</option>
                                    <option value="Horticulture/Agriculture">Horticulture/Agriculture</option>
                                    <option value="Aquamarine">Aquamarine</option>
                                    <option value="Metals & Engineering">Metals & Engineering</option>
                                    <option value="Furniture">Furniture</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <option value="Micro">Micro</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Macro">Macro</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required maxlength="30" />
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

    <script defer src="assets/js/autoUpdate.js"></script>
    <script src="assets/DataTables/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script defer src="assets/DataTables/datatables.min.js"></script>
    <script src="assets/DataTables/pdfmake.min.js"></script>
    <script src="assets/DataTables/vfs_fonts.js"></script>
    <script defer src="assets/js/functions.js"></script>
</body>

</html>