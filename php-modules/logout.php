<?php
session_start();

include "agents/logging.php";
logLogout($_SESSION['user_id'], $_SESSION['username']);

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: ../login.php");
exit();
