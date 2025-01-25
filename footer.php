<?php
date_default_timezone_set('Asia/Manila');
require_once 'session.php';
require_once 'class.php';
$db = new db_class();

$user_data = $db->user_account($_SESSION['user_id']);

$footer = "
<div class='container-fluid border-top bg-white'>
    <footer class='container d-flex flex-wrap justify-content-between py-3 my-4'>
        <div class='col-sm-4'>
            <small>
                <h4>Quick Links</h4>
                <ul class='list-unstyled'>
                    <li><a href='index.php' class='nav-link px-2 text-dark'>Home</a></li>
                    <li><a href='#' data-bs-toggle='modal' data-bs-target='#logoutModal' class='nav-link px-2 text-dark'>Logout</a></li>
                </ul>

                <h4>Developed by</h4>
                <ul class='list-unstyled'>
                    <li><a href='https://www.facebook.com/jarenquegan' class='nav-link px-2 text-dark' target='_blank'>Jaren Quegan</a></li>
                    <li><a href='https://www.facebook.com/mnchldluna' class='nav-link px-2 text-dark' target='_blank'>Adriana Lian Andres</a></li>
                    <li><a href='https://www.facebook.com/Kiansanchez05' class='nav-link px-2 text-dark' target='_blank'>Kian Luis Sanchez</a></li>
                    <li class='nav-link px-2 text-dark'>CSU-CICS * BSCS 2024</li>
                </ul>
            </small>
        </div>

        <div class='col-sm-4'>
            <small>
                <h4>Government Links</h4>
                <ul class='list-unstyled'>
                    <li><a href='http://president.gov.ph/' class='nav-link px-2 text-dark' target='_blank'>Office of the President</a></li>
                    <li><a href='http://ovp.gov.ph/' class='nav-link px-2 text-dark' target='_blank'>Office of the Vice President</a></li>
                    <li><a href='http://www.senate.gov.ph/' class='nav-link px-2 text-dark' target='_blank'>Senate of the Philippines</a></li>
                    <li><a href='http://www.congress.gov.ph/' class='nav-link px-2 text-dark' target='_blank'>House of Representatives</a></li>
                    <li><a href='http://sc.judiciary.gov.ph/' class='nav-link px-2 text-dark' target='_blank'>Supreme Court</a></li>
                    <li><a href='http://ca.judiciary.gov.ph/' class='nav-link px-2 text-dark' target='_blank'>Court of Appeals</a></li>
                    <li><a href='http://sb.judiciary.gov.ph/' class='nav-link px-2 text-dark' target='_blank'>Sandiganbayan</a></li>
                </ul>
            </small>
        </div>

        <div class='col-sm-4'>
            <small>
                <h4>Find Us</h4>
                <ul class='list-unstyled'>
                    <li class='nav-link px-2 text-dark'>
                        <i class='fa fa-envelope text-body-secondary' aria-hidden='true'></i> rstldost@gmail.com
                    </li>
                    <li class='nav-link px-2 text-dark'><i class='fa fa-phone text-body-secondary' aria-hidden='true'></i> 0935-786-2723</li>
                    <li class='nav-link px-2 text-dark'>
                        <i class='fa-brands fa-facebook text-body-secondary' aria-hidden='true'></i> DOST 02 - Regional
                        Standards and Testing Laboratory
                    </li>
                    <li class='nav-link px-2 text-dark'>
                        <i class='fa fa-building text-body-secondary' aria-hidden='true'></i>
                        #2 Dalan na Paccurofon, Corner Matunung St., Regional Government Center, Carig
                        Sur, Tuguegarao City, Cagayan
                    </li>
                    <li class='nav-link px-2 text-dark'>
                        <i class='fa fa-globe text-body-secondary' aria-hidden='true'></i>
                        <a class='text-decoration-none text-dark' href='https://region2.dost.gov.ph/' target='_blank'>
                            https://region2.dost.gov.ph/
                        </a>
                    </li>
                </ul>
            </small>
        </div>

        <div class='col-sm-12 d-flex flex-column align-items-center py-3 my-4 mb-4'>
            <div class='row text-center'>
                <div class='col-12'>
                    <div class='copy'>
                        <small class='mb-4'>
                            <h5>&copy; " . date('Y') . " Department of Science and Technology R02</h5>
                            Site developed by <strong><a href='https://www.facebook.com/jarenquegan' class='text-decoration-none text-dark' target='_blank'>Jaren Quegan</a></strong>, <strong><a href='https://www.facebook.com/mnchldluna' class='text-decoration-none text-dark' target='_blank'>Adriana Lian Andres</a></strong>, & <strong><a href='https://www.facebook.com/Kiansanchez05' class='text-decoration-none text-dark' target='_blank'>Kian Luis Sanchez</a></strong> - CSU-CICS * BSCS 2024
                        </small>
                    </div>
                </div>
            </div>

            <div class='row justify-content-center mt-3'>
                <div class='col-12'>
                    <ul class='list-inline d-flex justify-content-center'>
                        <li class='list-inline-item mx-2'>
                            <a href='http://www.gov.ph/' target='_blank'>
                                <img class='grayscale img-responsive' src='assets/images/repPhil.png' alt='Official Gazette' title='Government Website' height='60'>
                            </a>
                        </li>
                        <li class='list-inline-item mx-2'>
                            <a href='https://www.bagongpilipinastayo.com' target='_blank'>
                                <img class='grayscale img-responsive' src='assets/images/Bagong_Pilipinas_logo.png' alt='Bagong Pilipinas' title='Bagong Pilipinas Website' height='60'>
                            </a>
                        </li>
                        <li class='list-inline-item mx-2'>
                            <a href='http://www.dost.gov.ph/' target='_blank'>
                                <img class='grayscale img-responsive' src='assets/images/DOST-PNRI_Logo.png' alt='Department of Science and Technology' title='DOST Website' height='60'>
                            </a>
                        </li>
                        <li class='list-inline-item mx-2'>
                            <a href='index.php' target='_blank'>
                                <img class='grayscale img-responsive' src='assets/images/setup.png' alt='SETUP System' title='SETUP Management System' height='60'>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>
";
