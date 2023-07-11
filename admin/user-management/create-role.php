<?php
session_start();

// TODO: Change this to actual user role
$user_role = "Kucing Admin";

// Check if the user is not logged in, redirect to login page
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";

// MySQL query to fetch information from "users" table for the logged-in user
$username = $_SESSION["username"];
$sql = "SELECT first_name, last_name, employee_id FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, fetch the name and employee_id
    $row = $result->fetch_assoc();
    $name = $row["first_name"] . " " . $row["last_name"];
    $employeeId = $row["employee_id"];
} else {
    // User not found, handle the error
    header("Location: login.php");
}

$top_title = "Role Management";
if ($username == "nara") {
    $top_title .= "";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Table | <?= $top_title ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">

    <style>
        body {
            background-color: #F4EDE9;
        }
        .jt-orange {
            background-color: #ff5722;
        }
        .fa-4xl {
            font-size: 3em;
            line-height: 0.01637em;
            vertical-align: -0.27679em;
        }
    </style>
</head>
<body>

<!-- Left bar -->
<?= include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/sidebar.php" ; ?>

<div class="w3-threequarter w3-white" style="min-height: 100vh; margin-left: 25%;">
    <?= include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div style="margin-top: 16px; min-height: 100vh; background-color: #fbfbfb;">
        <!-- Ticketing Menu -->
        <div id="ticketing" class="w3-bar w3-white w3-border-bottom" style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Create Role</b></span>
        </div>

        <div class="w3-container w3-padding-16" style="background-color: #fbfbfb;">

            <!-- TODO: Create role form -->
            <form>
                <div class="w3-half">
                    <div class="w3-container">
                        <label>Role title: </label><input class="w3-input w3-border" name="title">
                        <label>Description: </label><textarea class="w3-input w3-border" name="description" rows="5"></textarea>
                    </div>
                </div>

                <div class="w3-half">
                    <div class="w3-container">
                        <h6>Permissions:</h6>
                        <div class="w3-half w3-container w3-padding-16 w3-border-gray w3-border w3-round-large">
                            Classification<br>
                            <input type="checkbox" class="w3-check" name="" id="">&nbsp;&nbsp;
                            <label for="">Create Classification</label><br>

                            <input type="checkbox" class="w3-check" name="" id="">&nbsp;&nbsp;
                            <label for="">Edit Classification</label><br>

                            <input type="checkbox" class="w3-check" name="" id="">&nbsp;&nbsp;
                            <label for="">Delete Classification</label><br>

                            <input type="checkbox" class="w3-check" name="" id="">&nbsp;&nbsp;
                            <label for="">View Classification</label><br>
                        </div>
                        <div class="w3-half w3-container w3-padding-16 w3-border-gray w3-border w3-round-large">
                            Articles<br>
                            <input type="checkbox" class="w3-check" name="" id="">&nbsp;&nbsp;
                            <label for="">Create Article</label><br>

                            <input type="checkbox" class="w3-check" name="" id="">&nbsp;&nbsp;
                            <label for="">Edit Article</label><br>

                            <input type="checkbox" class="w3-check" name="" id="">&nbsp;&nbsp;
                            <label for="">Delete Article</label><br>

                            <input type="checkbox" class="w3-check" name="" id="">&nbsp;&nbsp;
                            <label for="">View Article</label><br>
                        </div>
                    </div>
                </div>


            </form>


        </div>
    </div>
</div>

<script>

    function unlockAccount(username) {
        openURL("actions/enable-account.php?u=" + username);
    }

    function lockAccount(username) {
        openURL("actions/disable-account.php?u=" + username);
    }

    function resetPopup(username) {
        openPopup("actions/reset-password.php?u=" + username, "reset");
    }

    function openPopup(url, name) {
        var windowFeatures = "width=400,height=700,top=100,left=200,resizable=no,scrollbars=no,toolbar=no,menubar=no,location=no,status=no";

        window.open(url, name, windowFeatures);
    }

    function openURL(url) {
        window.location.href = url;
    }

    function dropdown(id) {
        var x = document.getElementById(id);
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }

</script>


</body>
</html>

<?php

    function calcDays($datetimestr) {
        date_default_timezone_set('Asia/Jakarta');
        $timestamp = strtotime($datetimestr);
        $seconds = time() - $timestamp;

        if ($seconds < 60) {
            $result = $seconds;
            $result = $result == 1 ? $result . " second" : $result . " seconds";
        } elseif ($seconds < 3600) {
            $result = floor($seconds / 60);
            $result = $result == 1 ? $result . " minute" : $result . " minutes";
        } elseif ($seconds < 86400) {
            $result = floor($seconds / 3600);
            $result = $result == 1 ? $result . " hour" : $result . " hours";
        } elseif ($seconds < 604800) {
            $result = floor($seconds / 86400);
            $result = $result == 1 ? $result . " day" : $result . " days";
        } elseif ($seconds < 2592000) {
            $result = floor($seconds / 604800);
            $result = $result == 1 ? $result . " week" : $result . " weeks";
        } elseif ($seconds < 31536000) {
            $result = floor($seconds / 2592000);
            $result = $result == 1 ? $result . " month" : $result . " months";
        } else {
            $result = floor($seconds / 31536000);
            $result = $result == 1 ? $result . " year" : $result . " years";
        }

        return $result . " ago";

    }

?>