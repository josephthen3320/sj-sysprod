<?php


include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';

function getUserDataByID($uid) {
    $conn = getConnUser();

    $sql = "SELECT * FROM user_accounts INNER JOIN users ON user_accounts.username = users.username WHERE user_accounts.id = '$uid'";
    $result = $conn->query($sql);

    $conn->close();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function getUserFullnameByID($uid) {
    $userdata = getUserDataByID($uid);

    return $userdata['first_name'] . " " . $userdata['last_name'];
}

function getRoleByUserId($uid) {
    $conn = getConnUser();

    $sql = "SELECT role FROM user_accounts WHERE id = '$uid'";
    $result = $conn->query($sql);
    $conn->close();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc()['role'];
    } else {
        return null;
    }
}