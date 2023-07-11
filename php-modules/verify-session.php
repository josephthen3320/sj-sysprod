<?php
// Check if the user is not logged in, redirect to login page
//session_start();

if (!isset($_SESSION["username"])) {
    session_destroy();
    $login_path = "http://" . $_SERVER['HTTP_HOST'] . '/login.php';
    header("Location: {$login_path}");
    exit();

}

    include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnUser();

    $un = $_SESSION["username"];
    $sql = "SELECT is_locked FROM user_accounts WHERE username = '$un'";
    $result = $conn->query($sql);

    $isLocked = $result->fetch_assoc()['is_locked'];
    $_SESSION['isLocked'] = $isLocked;



    $_SESSION['isLocked'] ? isLocked() : null;


    function isLocked() {
        $login_path = "https://" . $_SERVER['HTTP_HOST'] . '/login.php';
        header("Location: {$login_path}");
        exit();
    }