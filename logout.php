<?php
session_name("NewSession");
session_start();
session_destroy();

header('location: login-page.php');
