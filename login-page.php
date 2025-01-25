<?php

session_name("NewSession");
session_start();
date_default_timezone_set('Asia/Manila');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('assets/images/dostbg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(5px);
            z-index: -1;
            opacity: 0.4;
        }

        .container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .card {
            width: 100%;
            max-width: 1000px;
            height: 600px;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .row {
            height: 100%;
        }

        .image-section {
            position: relative;
            height: 100%;
            padding: 0;
        }

        .image-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .hero-section {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem;
        }

        .hero-title {
            color: #000;
            font-weight: bold;
            font-size: 2.5rem;
            line-height: 1.2;
        }

        .word-container {
            position: relative;
            overflow: hidden;
            height: 3rem;
        }

        .rotating-word {
            position: absolute;
            width: 100%;
            text-align: left;
            height: 3rem;
            line-height: 3rem;
            color: rgba(31, 53, 103, 1.00);
            font-weight: bold;
            font-size: 2rem;
        }

        .login-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 2rem;
        }

        .card-body {
            width: 100%;
        }

        .card-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-align: center;
            color: rgba(31, 53, 103, 1.00);
        }

        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            min-width: 400px;
        }

        .btn-dark {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: none;
            transition: all 0.3s ease;
        }

        .setup-logo {
            max-width: 231.78px;

            max-height: 77px;

            width: auto;

            height: auto;

            margin-bottom: 20px;

        }
    </style>
</head>

<body>
    <div class="background"></div>
    <div class="container">
        <div class="card">
            <div class="row g-0">
                <div class="col-lg-6 image-section">
                    <img src="assets/images/dostbg.png" alt="Login Image">
                    <div class="hero-section">
                        <img src="assets/images/setup.png" alt="Setup Logo" class="setup-logo">
                        <h1 class="hero-title">
                            D<span class="logo-color">O</span>ST-SETUP
                            <div class="word-container">
                                <div class="rotating-word">Service.</div>
                                <div class="rotating-word">Commitment.</div>
                                <div class="rotating-word">Innovation.</div>
                                <div class="rotating-word">Ethics.</div>
                                <div class="rotating-word">Nurturance.</div>
                                <div class="rotating-word">Collaboration.</div>
                                <div class="rotating-word">Excellence.</div>
                            </div>
                        </h1>
                    </div>
                </div>
                <div class="col-lg-6 login-section align">
                    Hello, let's
                    <h5 class="card-title text-center">SIGN IN</h5>
                    <form method="POST" class="user" action="login.php">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        </div>
                        <?php
                        if (isset($_SESSION['message'])) {
                            echo "<div class='alert alert-danger mb-3 form-control text-center'>" . $_SESSION['message'] . "</div>";
                            unset($_SESSION['message']);
                        }
                        ?>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-dark" style="background-color: rgba(31, 53, 103, 1.00);" name="login">CONTINUE</button>
                        </div>
                    </form>

                    <div class='text-secondary text-center'>
                        &copy; <?php echo date("Y") ?> DOST R02
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/DataTables/jquery-3.7.1.js"></script>
    <script defer src="assets/DataTables/datatables.min.js"></script>
    <script src="assets/DataTables/pdfmake.min.js"></script>
    <script src="assets/DataTables/vfs_fonts.js"></script>
    <script defer src="assets/js/functions.js"></script>
    <script src="assets/js/gsap.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const words = document.querySelectorAll(".rotating-word");
            const stagger = 2;
            const timeline = gsap.timeline({
                repeat: -1
            });

            timeline.from(words, {
                yPercent: 100,
                opacity: 0,
                duration: 1,
                stagger: stagger,
                ease: "power2.inOut"
            });
            timeline.to(
                words, {
                    yPercent: -100,
                    opacity: 1,
                    duration: 1,
                    stagger: stagger,
                    ease: "power2.inOut"
                },
                stagger
            );
        });
    </script>
</body>

</html>