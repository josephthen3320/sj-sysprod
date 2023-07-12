<?php
session_start();

$page_title = "User Management";

// TODO: Change this to actual user role
$user_role = "Kucing Admin";

// Check if the user is not logged in, redirect to login page
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";
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

$top_title = "User Management";
if ($username == "nara") {
    $top_title .= "";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $top_title ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">

    <style>
        body {
            background-color: #fbfbfb
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
<?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/sidebar.php" ; ?>

<div class="w3-threequarter w3-white sj-content" style="min-height: 100vh; margin-left: 25%; margin-bottom: 64px; background-color: #fbfbfb">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div style="margin-top: 16px; min-height: 50vh;">
        <!-- Ticketing Menu -->
        <div id="ticketing" class="w3-bar w3-white w3-border-bottom" style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>User Account</b></span>
        </div>

        <div class="w3-container w3-padding-16" style="background-color: #fbfbfb">
            <!-- Add User Button -->
            <div class="w3-quarter w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button" onclick="openPopup('create-user.php', 'admin-popup')" style="height: 180px; width: 100%;">
                    <div class="w3-display-middle">
                        <div class="w3-container w3-padding">
                            <i class="fa-solid fa-user-plus fa-4xl" style="color: green;"></i><br><br>
                        </div>
                    </div>
                    <div class="w3-container w3-padding-32 w3-display-bottommiddle">
                        <span class="w3-large"><b>New</b></span>
                    </div>
                </div>
            </div>
            <!-- Manage User Button -->
            <div class="w3-quarter w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button" onclick="openPopup('manage.php', 'admin-popup')" style="height: 180px; width: 100%;">
                    <div class="w3-display-middle">
                        <div class="w3-container w3-padding">
                            <i class="fa-solid fa-user-gear fa-4xl" style="color: royalblue;"></i><br><br>
                        </div>
                    </div>
                    <div class="w3-container w3-padding-32 w3-display-bottommiddle">
                        <span class="w3-large"><b>Manage</b></span>
                    </div>
                </div>
            </div>
            <!-- User Table Button -->
            <div class="w3-quarter w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button" onclick="openURL('user-table.php')" style="height: 180px; width: 100%;">
                    <div class="w3-display-middle">
                        <div class="w3-container w3-padding">
                            <i class="fa-solid fa-table-layout fa-4xl" style="color: darkred;"></i><br><br>
                        </div>
                    </div>
                    <div class="w3-container w3-padding-32 w3-display-bottommiddle">
                        <span class="w3-large"><b>User Table</b></span>
                    </div>
                </div>
            </div>


        </div>

        <div class="w3-container w3-cell-row w3-padding-16" style="background-color: #fbfbfb">
            <div class="w3-half w3-cell w3-container">
                <div class=" w3-card-2">
                    <div class="w3-bar w3-container w3-text-white" style=" background-color: #0B293C; padding: 16px 40px;">
                        <h4>
                            <i class="fa-solid fa-messages"></i> &nbsp;&nbsp;
                            User Activity Insights
                        </h4>
                    </div>
                    <div class="w3-container w3-white">
                        <?php include "chart-user-activity.php"; ?>
                    </div>
                </div>
            </div>
            <div class="w3-half w3-cell w3-container">
                <div class=" w3-card-2">
                    <div class="w3-bar w3-container w3-text-white" style=" background-color: #0B293C; padding: 16px 40px;">
                        <h4>
                            <i class="fa-solid fa-messages"></i> &nbsp;&nbsp;
                            Latest Activities
                        </h4>
                    </div>
                    <div class="w3-white">
                        <?php include "table-user-activity.php"; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Roles -->
    <div style="margin-top: 16px; min-height: 50vh;">
        <div id="" class="w3-bar w3-white w3-border-bottom" style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Role Management</b></span>
        </div>

        <div class="w3-container w3-padding-16" style="background-color: #fbfbfb">
            <!-- Add User Button -->
            <div class="w3-quarter w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button" onclick="openURL('create-role.php')" style="height: 180px; width: 100%;">
                    <div class="w3-display-middle">
                        <div class="w3-container w3-padding">
                            <i class="fa-solid fa-layer-plus fa-4xl" style="color: green;"></i><br><br>
                        </div>
                    </div>
                    <div class="w3-container w3-padding-32 w3-display-bottommiddle">
                        <span class="w3-large"><b>New</b></span>
                    </div>
                </div>
            </div>

            <!-- Role Table Button -->
            <div class="w3-quarter w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button" onclick="openURL('role-table.php')" style="height: 180px; width: 100%;">
                    <div class="w3-display-middle">
                        <div class="w3-container w3-padding">
                            <i class="fa-solid fa-table-layout fa-4xl" style="color: royalblue;"></i><br><br>
                        </div>
                    </div>
                    <div class="w3-container w3-padding-32 w3-display-bottommiddle">
                        <span class="w3-large"><b>Role Table</b></span>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<script>
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
<?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/bottombar.php" ?>
</body>
</html>