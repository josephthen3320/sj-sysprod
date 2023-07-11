<?php
session_start();

$page_title = "Production Centre";

// TODO: Change this to actual user role
$user_role = "Kucing Admin";

// Check if the user is not logged in, redirect to login page
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnUser();

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

$top_title = "";
if ($username == "nara") {
    $top_title .= "Admin ";
}
$top_title .= "Dashboard";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/sidebar.php" ; ?>

    <div class="w3-threequarter w3-white sj-content" style="min-height: 100vh; margin-left: 25%;">
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

        <div style="margin-top: 16px; min-height: 100vh; background-color: #fbfbfb">
            <!-- Dashboard menu -->
            <div id="user-management" class="w3-bar w3-white w3-border-bottom " style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
                <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Overview</b></span>
            </div>

            <div class="w3-container w3-cell-row w3-padding-16">
                <!-- Classification Button -->
                <div class="w3-quarter w3-padding">
                    <div class="w3-card-2 w3-white w3-round w3-button w3-cell-row" onclick="openURL('classification.php')" style="height: 100px; width: 100%;">
                        <div class="w3-container w3-cell w3-cell-middle">
                            <i class="fa-solid fa-database fa-4xl"></i>
                        </div>
                        <div class="w3-container w3-cell w3-cell-middle w3-left-align">
                            <h3><b>Classification</b></h3>
                        </div>
                    </div>
                </div>

                <!-- Article Button -->
                <div class="w3-quarter w3-padding">
                    <div class="w3-card-2 w3-white w3-round w3-button w3-cell-row buttoncontainer" onclick="openURL('article.php')" style="height: 100px; width: 100%;">
                        <div class="w3-container w3-cell w3-cell-middle">
                            <i class="fa-solid fa-shirt fa-4xl"></i>
                        </div>
                        <div class="w3-container w3-cell w3-cell-middle w3-left-align">
                            <h3><b>Article</b></h3>
                        </div>
                    </div>
                </div>

                <script>
                    var div = document.querySelector('.fa-shirt');
                    var container = document.querySelector('.buttoncontainer');

                    container.addEventListener('mouseenter', function() {
                        div.classList.add('fa-flip');
                    });

                    container.addEventListener('mouseleave', function() {
                        div.classList.remove('fa-flip');
                    })
                </script>

                <!-- Worksheet Button -->
                <div class="w3-quarter w3-padding">
                    <div class="w3-card-2 w3-white w3-round w3-button w3-cell-row" onclick="openURL('worksheet.php')" style="height: 100px; width: 100%;">
                        <div class="w3-container w3-cell w3-cell-middle">
                            <i class="fa-solid fa-file-lines fa-4xl"></i>
                        </div>
                        <div class="w3-container w3-cell w3-cell-middle w3-left-align">
                            <h3><b>Worksheet</b></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w3-container w3-cell-row w3-padding-16">
                <div class="w3-half w3-cell w3-container">
                    <div class=" w3-card-2">
                        <div class="w3-bar w3-container w3-text-white" style=" background-color: #0B293C; padding: 16px 40px;">
                            <h4>
                                <i class="fa-solid fa-calendar-days"></i> &nbsp;&nbsp;
                                <?= date("d M Y"); ?>
                            </h4>
                        </div>
                        <div class="w3-bar-block w3-text-black w3-white">
                            <div class="w3-bar-item w3-leftbar w3-border-red w3-hover-light-grey" style="padding: 16px 40px;">
                                <span><b>Subject 1</b></span> <br>
                                <span>Detail line 1</span>
                            </div>
                            <div class="w3-bar-item w3-leftbar w3-border-green w3-hover-light-grey" style="padding: 16px 40px;">
                                <span><b>Subject 2</b></span> <br>
                                <span>Detail line 2</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w3-half w3-cell w3-container">
                    <div class=" w3-card-2">
                        <div class="w3-bar w3-container w3-text-white" style=" background-color: #0B293C; padding: 16px 40px;">
                            <h4>
                                <i class="fa-solid fa-messages"></i> &nbsp;&nbsp;
                                Updates
                            </h4>
                        </div>
                        <div class="w3-bar-block w3-text-black w3-white">
                            <div class="w3-bar-item w3-leftbar w3-border-blue w3-hover-light-grey" style="padding: 16px 40px;">
                                <div class="w3-container w3-cell w3-cell-middle">
                                    <i class="fa-solid fa-exclamation-circle fa-2xl w3-text-blue"></i>
                                </div>
                                <div class="w3-container w3-cell w3-cell-middle">
                                    <span>Sysprod Updated</span> <br>
                                    <span><b><?= date("Y.m.d") ?></b></span>
                                </div>
                            </div>
                            <div class="w3-bar-item w3-leftbar w3-border-orange w3-hover-light-grey" style="padding: 16px 40px;">
                                <div class="w3-container w3-cell w3-cell-middle">
                                    <i class="fa-solid fa-bug fa-2xl w3-text-orange"></i>
                                </div>
                                <div class="w3-container w3-cell w3-cell-middle">
                                    <span>New Ticket</span> <br>
                                    <span>
                                        <b><?= date("Y.m.d") ?></b>
                                    </span>
                                </div>
                            </div>
                            <div class="w3-bar-item w3-leftbar w3-border-red w3-hover-light-grey" style="padding: 16px 40px;">
                                <div class="w3-container w3-cell w3-cell-middle">
                                    <i class="fa-solid fa-face-frown-open fa-2xl w3-text-red"></i>
                                </div>
                                <div class="w3-container w3-cell w3-cell-middle">
                                    <span>Service Down: SysProd</span> <br>
                                    <span>
                                        <b><?= date("Y.m.d - H:i:s") ?></b>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- TODO: DELETE THIS LATER -->
            <div class="w3-container w3-cell-row w3-padding-16">
                <div class="w3-cell w3-container">
                    <div class=" w3-card-2">
                        <div class="w3-bar w3-container w3-text-white" style=" background-color: #0B293C; padding: 16px 40px;">
                            <h4>
                                <i class="fa-solid fa-messages"></i> &nbsp;&nbsp;
                                Sysprod Flowchart Reference
                            </h4>
                        </div>
                        <div class="w3-bar-block w3-text-black w3-white">
                            <img class="" src="Sysprod_flowchart.png" width="100%">

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

            function openTabURL(url, target) {
                window.open(url, target);
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