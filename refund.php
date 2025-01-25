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
        <h3 class="text-center" style="color: white; padding: 2px;">REFUND RECORDS</h3>
    </div>

    <div class="container-fluid mt-3">
        <div class="card shadow mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="refundDataTable" style="width:100%" class="display compact table table-bordered table-striped table-condensed small">
                        <thead>
                            <tr class="fw-bold">
                                <th style="text-align: left;" class="content-fit text-center"></th>
                                <th style="text-align: left;">Project Title</th>
                                <th style="text-align: left;">Project Spin No.</th>
                                <td style="text-align: left;">Beneficiary</th>
                                <td style="text-align: left;">Amount</th>
                                <td style="text-align: left;">OR Number</th>
                                <td style="text-align: left;">Date Recorded</th>
                                <th style="text-align: left;" class="content-fit">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tbl_refund = $db->conn->query("SELECT refund.*, project.* 
                            FROM `refund` 
                            INNER JOIN `project` ON refund.project_id = project.project_id
                            ORDER BY refund.refund_id");
                            $i = 1;
                            while ($fetch = $tbl_refund->fetch_array()) {
                            ?>
                                <tr>
                                    <td style="text-align: left;" class="content-fit text-center"><?php echo $i++; ?></td>
                                    <td style="text-align: left;"><?php echo $fetch['title'] ?></td>
                                    <td style="text-align: left;"><?php echo $fetch['spin_no'] ?></td>
                                    <td style="text-align: left;"><?php echo $fetch['payee'] ?></td>
                                    <td style="text-align: left;"><?php echo "&#8369; " . number_format($fetch['pay_amount'], 2) ?></td>
                                    <td style="text-align: left;"><?php echo $fetch['or_number'] ?>

                                    </td>
                                    <?php
                                    $date_created = $fetch['date_created'];

                                    $date = new DateTime($date_created);
                                    $formatted_date = $date->format('Y-m-d');
                                    ?>
                                    <td style="text-align: left;"><?php echo $formatted_date; ?></td>
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
                                                <?php if ($account_type == 'Administrator' || $account_type == 'Cashier') { ?>
                                                    <!-- <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#updateRefund<?php echo $fetch['refund_id'] ?>">Edit</a></li> -->
                                                    <li><a class="dropdown-item small" href="#" data-bs-toggle="modal" data-bs-target="#deleteRefund<?php echo $fetch['refund_id'] ?>">Delete</a></li>
                                                <?php } else { ?>
                                                    <li><a class="dropdown-item small" href="#" disabled>No Access</a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="deleteRefund<?php echo $fetch['refund_id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title text-white">System Information</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">Are you sure you want to delete this record?</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                                <a class="btn btn-danger" href="deleteRefund.php?project_sched_id=<?php echo $fetch['project_schedule'] ?>">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRefund" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="save_refund.php">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Refund Form</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row align-items-center">
                                                <div class="col-md-8 text-start">
                                                    <h4><strong id="projectTitle">PROJECT TITLE</strong></h4>
                                                    <i class="fa-regular fa-building"></i> <span id="firmName">Firmname</span>&emsp;&emsp;&emsp;
                                                    <i class="fa-regular fa-user"></i> <span id="beneficiaryName">Beneficiary</span>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <p>
                                                        Refund Progress<br>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar bg-success"
                                                            role="progressbar"
                                                            id="refundProgressBar"
                                                            style="width: 0%;"
                                                            aria-valuenow="0"
                                                            aria-valuemin="0"
                                                            aria-valuemax="100">
                                                            0%
                                                        </div>
                                                    </div>
                                                    </p>
                                                </div>
                                            </div>
                                            <div style="text-align: right;">Project Spin No.:
                                                <strong id="projectSpinNo">00-0000-000-00-00-0</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3" id="formField">
                            <div class="col-md-12">
                                <label for="project_search" class="form-label">Search by Spin No. or Title</label>
                                <input list="project_list" name="project_search" class="form-control" id="project_search" required>
                                <datalist id="project_list">
                                    <?php
                                    $tbl_project = $db->display_project();
                                    while ($fetch = $tbl_project->fetch_array()) {
                                        if ($fetch['status'] == 2) {
                                    ?>
                                            <option data-id="<?php echo $fetch['project_id'] ?>"
                                                data-spin="<?php echo $fetch['spin_no'] ?>"
                                                data-title="<?php echo strtoupper($fetch['title']) ?>"
                                                data-firmname="<?php echo htmlspecialchars($fetch['firmname']) ?>"
                                                data-beneficiary="<?php echo htmlspecialchars($fetch['firstname'] . " " . substr($fetch['middlename'], 0, 1) . ". " . $fetch['lastname']) ?>"
                                                value="<?php echo $fetch['spin_no'] . ' - ' . $fetch['title'] ?>">
                                            </option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </datalist>
                                <input type="hidden" name="project_id" id="project_id">
                                <input type="hidden" name="spin_no" id="spin_no">
                            </div>

                            <script>
                                document.getElementById('spin_no').addEventListener('input', function() {
                                    let options = document.getElementById('spin_no_list').options;
                                    for (let i = 0; i < options.length; i++) {
                                        if (options[i].value === this.value) {
                                            document.getElementById('project_id').value = options[i].getAttribute('data-id');
                                            break;
                                        }
                                    }
                                });
                            </script>

                            <div class="col-md-12">
                                <label for="payee" class="form-label">Beneficiary</label>
                                <input type="text" id="payee" name="payee" class="form-control" readonly />
                                <input type="hidden" id="balance" name="balance" />
                                <input type="hidden" id="payable" name="payable" />
                                <input type="hidden" id="monthly" name="monthly" />
                            </div>

                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="deferred" id="deferPayment" name="defer_payment">
                                    <label class="form-check-label" for="deferPayment">
                                        Defer Payment
                                    </label>
                                </div>
                            </div>

                            <div class="row text-center mb-3" id="message" style="display: none;">
                                <div class="col-md-12">
                                    <span class="text-danger">
                                        Payment deferred, uncheck it to insert payment.
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr id="paymentDetailsLine">
                        <div id="paymentDetails" class="row g-3">
                            <div class="col-md-6">
                                <label for="amountOptions" class="form-label">Select Amount Type <i>(Optional)</i></label>
                                <div id="amountOptions">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="amountType" id="useMonthlyAmount" value="monthly">
                                        <label class="form-check-label" for="useMonthlyAmount">
                                            Monthly Payable Amount
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="amountType" id="useBalanceAmount" value="balance">
                                        <label class="form-check-label" for="useBalanceAmount">
                                            Total Payable Amount
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="refund" class="form-label">Amount</label>
                                <input type="number" class="form-control" name="refund" id="refund" step="0.01" required />
                            </div>

                            <div class="col-md-10" id="" style="display: block;">
                                <label for="or_number" class="form-label">OR Number</label>
                                <input type="text" class="form-control" name="or_number" id="or_number" required />
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-primary w-100" id="generateOrNumber" style="display: block;">Generate</button>
                            </div>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="save" id="saveButton" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#project_search').on('input', function() {
                var selected = $('#project_list option[value="' + $(this).val() + '"]');
                var projectId = selected.data('id');
                var spinNo = selected.data('spin');
                var title = selected.data('title');
                var firmName = selected.data('firmname');
                var beneficiary = selected.data('beneficiary');

                if (projectId) {
                    $('#project_id').val(projectId);
                    $('#spin_no').val(spinNo);

                    $('#projectTitle').text(title);
                    $('#firmName').text(firmName);
                    $('#beneficiaryName').text(beneficiary);
                    $('#projectSpinNo').text(spinNo);

                    $.ajax({
                        url: "get_field.php",
                        type: "GET",
                        data: {
                            project_id: projectId
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

                            var refundRate = (fields.payable - fields.balance) / fields.payable * 100;
                            $('#refundProgressBar').css('width', refundRate + '%')
                                .attr('aria-valuenow', refundRate)
                                .text(refundRate.toFixed(2) + '%');
                        }
                    });
                } else {

                    $('#project_id').val('');
                    $('#spin_no').val('');
                    $('#payee').val('');
                    $('#balance').val('');
                    $('#payable').val('');
                    $('#monthly').val('');
                    $('#monthlyAmount').text('₱ 0.00');
                    $('#balanceAmount').text('₱ 0.00');
                    $('#payableAmount').text('₱ 0.00');

                    $('#projectTitle').text('PROJECT TITLE');
                    $('#firmName').text('Firmname');
                    $('#beneficiaryName').text('Beneficiary');
                    $('#projectSpinNo').text('00-0000-000-00-00-0');
                    $('#refundProgressBar').css('width', '0%')
                        .attr('aria-valuenow', 0)
                        .text('0%');
                }
            });
        });
    </script>

    <script>
        document.getElementById('generateOrNumber').addEventListener('click', function() {
            var projectId = document.getElementById('project_id').value;

            if (!projectId) {
                alert("Please select a Spin No. first");
                return;
            }

            fetch('generate_or_number.php?project_id=' + projectId)
                .then(response => response.text())
                .then(orNumber => {
                    document.getElementById('or_number').value = orNumber;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("An error occurred while generating the OR number. Please try again.");
                });
        });
    </script>

    <script>
        document.getElementById('refund').addEventListener('input', function(e) {
            let value = this.value;
            let cursorPosition = this.selectionStart;

            let filteredValue = '';
            let periodFound = false;
            for (let i = 0; i < value.length; i++) {
                if (value[i] === '.' && !periodFound) {
                    filteredValue += '.';
                    periodFound = true;
                } else if (/\d/.test(value[i])) {
                    filteredValue += value[i];
                }
            }

            if (this.value !== filteredValue) {
                this.value = filteredValue;

                if (value[cursorPosition - 1] === '.' && periodFound) {
                    this.setSelectionRange(filteredValue.length, filteredValue.length);
                } else {
                    let newPosition = cursorPosition - (value.length - filteredValue.length);
                    this.setSelectionRange(newPosition, newPosition);
                }
            }

            let balance = parseFloat(document.getElementById('balance').value);
            let monthly = parseFloat(document.getElementById('monthly').value);
            let refundValue = parseFloat(filteredValue);

            monthly = Math.round(monthly * 100) / 100;
            balance = Math.round(balance * 100) / 100;

            if (isNaN(refundValue)) {
                refundValue = 0;
            }

            if (!isNaN(refundValue)) {
                if (refundValue == monthly) {
                    document.getElementById('useMonthlyAmount').checked = true;
                    document.getElementById('useBalanceAmount').checked = false;
                } else if (refundValue == balance) {
                    document.getElementById('useBalanceAmount').checked = true;
                    document.getElementById('useMonthlyAmount').checked = false;
                } else {
                    document.getElementById('useMonthlyAmount').checked = false;
                    document.getElementById('useBalanceAmount').checked = false;
                }
            }
        });

        document.getElementById('useMonthlyAmount').addEventListener('change', function() {
            if (this.checked) {
                let monthly = parseFloat(document.getElementById('monthly').value);
                let balance = parseFloat(document.getElementById('balance').value);

                monthly = Math.round(monthly * 100) / 100;
                balance = Math.round(balance * 100) / 100;

                if (monthly > balance) {
                    document.getElementById('refund').value = balance.toFixed(2);
                    alert("The monthly amount exceeds the remaining balance, so the value has been automatically adjusted to not exceed the remaining balance.");

                } else {
                    document.getElementById('refund').value = monthly.toFixed(2);
                }
            }

            if (!isNaN(refundValue)) {
                if (refundValue == monthly) {
                    document.getElementById('useMonthlyAmount').checked = true;
                    document.getElementById('useBalanceAmount').checked = false;
                } else if (refundValue == balance) {
                    document.getElementById('useBalanceAmount').checked = true;
                    document.getElementById('useMonthlyAmount').checked = false;
                } else {
                    document.getElementById('useMonthlyAmount').checked = false;
                    document.getElementById('useBalanceAmount').checked = false;
                }
            }
        });

        document.getElementById("saveButton").addEventListener("click", function(event) {
            let refundInput = document.getElementById("refund");
            let ba = parseFloat(document.getElementById("balance").value);
            let monthly = parseFloat(document.getElementById("monthly").value);
            let refundValue = parseFloat(refundInput.value);

            if (isNaN(refundValue)) {
                refundValue = 0;
            }

            let adjustmentMade = false;

            monthly = Math.round(monthly * 100) / 100;
            balance = Math.round(ba * 100) / 100;

            let deferPayment = document.getElementById("deferPayment");

            if (refundValue < monthly) {
                if (monthly > ba) {
                    refundInput.value = ba.toFixed(2);
                } else {
                    refundInput.value = monthly.toFixed(2);
                    if (!deferPayment.checked) {
                        alert(
                            "The entered amount is less than the required monthly amount. The value has been automatically adjusted to the monthly amount."
                        );
                        event.preventDefault();
                    }
                }
                adjustmentMade = true;
            } else if (refundValue > balance) {
                refundInput.value = ba.toFixed(2);
                if (!deferPayment.checked) {
                    alert(
                        "The entered amount exceeds the remaining balance. The value has been automatically adjusted to not exceed the remaining balance."
                    );
                    event.preventDefault();
                }
                adjustmentMade = true;
            }

            refundValue = parseFloat(refundInput.value);
            if (!isNaN(refundValue)) {
                if (Math.abs(refundValue - monthly) < 0.001) {
                    document.getElementById("useMonthlyAmount").checked = true;
                    document.getElementById("useBalanceAmount").checked = false;
                } else if (Math.abs(refundValue - balance) < 0.001) {
                    document.getElementById("useBalanceAmount").checked = true;
                    document.getElementById("useMonthlyAmount").checked = false;
                } else {
                    document.getElementById("useMonthlyAmount").checked = false;
                    document.getElementById("useBalanceAmount").checked = false;
                }
            }
        });

        document.getElementById('useBalanceAmount').addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('refund').value = parseFloat(document.getElementById('balance').value).toFixed(2);
            }
        });

        document.querySelectorAll('input[name="amountType"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (!document.querySelector('input[name="amountType"]:checked')) {
                    document.getElementById('refund').value = '';
                }
            });
        });
    </script>

    <script>
        document.getElementById('deferPayment').addEventListener('change', function() {
            let paymentDetails = document.getElementById('paymentDetails');
            let calcTable = document.getElementById('calcTable');
            let line = document.getElementById('line');
            let message = document.getElementById('message');
            let refundField = document.getElementById('refund');
            let orNumberField = document.getElementById('or_number');
            let paymentDetailsLine = document.getElementById('paymentDetailsLine');

            if (this.checked) {
                paymentDetails.style.visibility = 'hidden';
                paymentDetails.style.height = '0';

                calcTable.style.visibility = 'hidden';
                calcTable.style.height = '0';

                line.style.display = 'none';
                paymentDetailsLine.style.display = 'none';

                message.style.display = 'block';

                refundField.removeAttribute('required');
                orNumberField.removeAttribute('required');
            } else {
                paymentDetails.style.visibility = 'visible';
                paymentDetails.style.height = 'auto';

                calcTable.style.visibility = 'visible';
                calcTable.style.height = 'auto';

                line.style.display = 'block';
                paymentDetailsLine.style.display = 'block';

                message.style.display = 'none';

                refundField.setAttribute('required', 'required');
                orNumberField.setAttribute('required', 'required');
            }
        });
    </script>

    <a class="scroll-to-top rounded-circle" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div id="container-floating">
        <div id="floating-button">
            <button type="button" class="custom-btn" data-bs-toggle="modal" data-bs-target="#addRefund" id="   "
                <?php echo ($account_type != 'Administrator' && $account_type != 'Cashier') ? 'disabled' : ''; ?>>
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

    <?php echo $footer; ?>

    <script>
        document.getElementById('editUserForm1<?php echo $user_data['user_id']; ?>').addEventListener('submit', function(event) {
            var password = document.getElementById('password2').value;
            var passwordError = document.getElementById('password-error2');

            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (!passwordRegex.test(password)) {

                passwordError.style.display = 'block';

                if (!deferPayment.checked) {
                    event.preventDefault();
                }
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