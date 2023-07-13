<?php

session_start();

if (isset($_GET['u'])){

    include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/agents/logging.php";

    $conn = getConnUser();

    $username = $_GET['u'];
    $userId = getUserIdByUsername($_GET['u']);

    logGeneric($_SESSION['user_id'], 4, "DISABLE USER; user={$username}({$userId});");

    $sql = "UPDATE user_accounts SET is_locked = 1 WHERE username = '$username'";
    $conn->query($sql);
    $conn->close();

    echo "<script src='/assets/js/utils.js'></script><script>goBack();</script>";
}