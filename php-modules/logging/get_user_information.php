<?php

    function getUserIdByUsername($u) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
        $conn = getConnUser();

        $ui_sql = "SELECT id FROM user_accounts WHERE username = '$u'";
        $ui_result = $conn->query($ui_sql);

        if ($ui_result->num_rows > 0) {
            $uid = $ui_result->fetch_assoc()['id'];
            $conn->close();
            return $uid;
        } else {
            $conn->close();
            return null;
        }
    }
    
    function getUsernameById($uid) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
        $conn = getConnUser();

        $ui_sql = "SELECT username FROM user_accounts WHERE id = '$uid'";
        $ui_result = $conn->query($ui_sql);

        if ($ui_result->num_rows > 0) {
            $u = $ui_result->fetch_assoc()['username'];
            $conn->close();
            return $u;
        } else {
            $conn->close();
            return null;
        }
    }

    function getUserFullnameByUsername($u) {
        return getUserFirstNameByUsername($u) . " " . getUserLastNameByUsername($u);
    }

    function getUserFirstNameByUsername($u) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
        $conn = getConnUser();

        $ui_sql = "SELECT first_name FROM users WHERE username = '$u'";
        $ui_result = $conn->query($ui_sql);

        if ($ui_result->num_rows > 0) {
            $ui_row = $ui_result->fetch_assoc();
            $conn->close();
            return $ui_row['first_name'];
        } else {
            $conn->close();
            return null;
        }
    }

    function getUserLastNameByUsername($u) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
        $conn = getConnUser();

        $ui_sql = "SELECT last_name FROM users WHERE username = '$u'";
        $ui_result = $conn->query($ui_sql);

        if ($ui_result->num_rows > 0) {
            $ui_row = $ui_result->fetch_assoc();
            $conn->close();
            return $ui_row['last_name'];
        } else {
            $conn->close();
            return null;
        }
    }