<?php
session_start();

$page_title = "Support";

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

$top_title = "Ticketing System";
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
    <?= include  $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div style="margin-top: 16px; min-height: 100vh;">
        <!-- Ticketing Menu -->
        <div id="ticketing" class="w3-bar w3-white w3-border-bottom" style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Ticketing System</b></span>
        </div>

        <div class="w3-container w3-padding-16" style="background-color: #fbfbfb">
            <!-- Submit Ticket Button -->
            <div class="w3-quarter w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button" onclick="openURL('ticketing/new-ticket.php')" style="height: 180px; width: 100%;">
                    <div class="w3-display-middle">
                        <div class="w3-container w3-padding">
                            <i class="fa-solid fa-bug fa-4xl" style="color: orangered;"></i><br><br>
                        </div>
                    </div>
                    <div class="w3-container w3-padding-32 w3-display-bottommiddle">
                        <span class="w3-large"><b>Submit Ticket</b></span>
                    </div>
                </div>
            </div>

            <!-- My Tickets Button -->
            <div class="w3-quarter w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button" onclick="openURL('ticketing/new-ticket.php')" style="height: 180px; width: 100%;">
                    <div class="w3-display-middle">
                        <div class="w3-container w3-padding">
                            <i class="fa-solid fa-list fa-4xl" style="color: dodgerblue;"></i><br><br>
                        </div>
                    </div>
                    <div class="w3-container w3-padding-32 w3-display-bottommiddle">
                        <span class="w3-large"><b>My Tickets</b></span>
                    </div>
                </div>
            </div>

            <!-- View Tickets Button -->
            <?php
                $view_btn = "<div class=\"w3-quarter w3-center w3-padding\">
                                <div class=\"w3-container w3-card-2 w3-display-container w3-white w3-round w3-button\" onclick=\"openURL('ticketing/view-all.php')\" style=\"height: 180px; width: 100%;\">
                                    <div class=\"w3-display-middle\">
                                        <div class=\"w3-container w3-padding\">
                                            <i class=\"fa-solid fa-list-check fa-4xl\" style=\"color: seagreen;\"></i><br><br>
                                        </div>
                                    </div>
                                    <div class=\"w3-container w3-padding-32 w3-display-bottommiddle\">
                                        <span class=\"w3-large\"><b>View Tickets</b></span>
                                    </div>
                                </div>
                            </div>";

                if ($username == "nara") {
                    echo $view_btn;
                }
            ?>
        </div>



        <?php
        require_once "ticketing/php/db-its.php";

        $sql = "SELECT COUNT(*) AS count FROM ticket WHERE status != 'closed'";
        $result = $conn->query($sql);
        $total_count = $result->fetch_assoc()['count'];

        $sql = "SELECT COUNT(*) AS count FROM ticket WHERE DATE(date_created) = CURDATE();";
        $result = $conn->query($sql);
        $today_count = $result->fetch_assoc()['count'];

        $sql = "SELECT COUNT(*) AS count FROM ticket WHERE DATE(date_updated) = CURDATE() AND status = 'closed';";
        $result = $conn->query($sql);
        $today_resolved = $result->fetch_assoc()['count'];

        $sql = "SELECT AVG(TIMESTAMPDIFF(SECOND, date_created, date_updated)) AS average_time FROM ticket;";
        $result = $conn->query($sql);
        $average_time = $result->fetch_assoc()['average_time'];
        // Calculate the average time in hours, minutes, and seconds
        $hours = floor($average_time / 3600);
        $minutes = floor(($average_time % 3600) / 60);
        $seconds = $average_time % 60;

        ?>

        <div class="w3-container w3-padding-16" style="background-color: #fbfbfb">
            <!-- Total Tickets -->
            <div class="w3-third w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <div class="w3-container w3-padding">
                            <i class="fa-solid fa-ticket fa-4xl" style="color: royalblue;"></i><br><br>
                            <b><span class="w3-xlarge"><?= $total_count ?></span><br><span class="w3-text-grey">Active Tickets</span><br><br></b>
                        </div>
                    </div>
                </div>
            </div>
            <!-- New Tickets -->
            <div class="w3-third w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <div class="w3-container w3-padding">
                            <i class="fa-solid fa-message-plus fa-4xl" style="color: forestgreen;"></i><br><br>
                            <b><span class="w3-xlarge"><?= $today_count ?></span><br><span class="w3-text-grey">New Tickets</span><br><br></b>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Closed Tickets -->
            <div class="w3-third w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <div class="w3-container w3-padding">
                            <i class="fa-solid fa-thumbs-up fa-4xl" style="color: darkred;"></i><br><br>
                            <b><span class="w3-xlarge"><?= $today_resolved ?></span><br><span class="w3-text-grey">Closed Tickets</span><br><br></b>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w3-container w3-cell-row w3-padding-16">
            <div class="w3-half w3-cell w3-container">
                <div class=" w3-card-2">
                    <!-- Module Header -->
                    <div class="w3-bar w3-container w3-text-white" style=" background-color: #0B293C; padding: 16px 40px;">
                        <h4>
                            <i class="fa-solid fa-chart-line"></i> &nbsp;&nbsp;
                            Today's Insights
                        </h4>
                    </div>
                    <!-- End Header-->
                    <!-- Module Content -->
                    <div class="w3-bar-block w3-text-black w3-white">
                        <!-- Message Content -->
                        <div class="w3-bar-item w3-hover-light-grey w3-cell-row" style="padding: 16px 24px;">
                            <div class="w3-container w3-cell w3-center w3-quarter">
                                <i class="fa-solid fa-inbox-in fa-xl w3-text-blue w3-center"></i>
                            </div>
                            <div class="w3-container w3-cell w3-cell-middle w3-center w3-quarter" style="font-weight: bold; color: darkgreen;">
                                <span class="w3-large"><?= $today_count ?></span>
                            </div>
                            <div class="w3-container w3-cell w3-cell-middle w3-half">
                                <span class="w3-large">Received Tickets</span>
                            </div>
                        </div>
                        <!-- End Message -->
                        <!-- Messages Content -->
                        <div class="w3-bar-item w3-hover-light-grey w3-cell-row" style="padding: 16px 24px;">
                            <div class="w3-container w3-cell w3-center w3-quarter">
                                <i class="fa-solid fa-badge-check fa-xl w3-text-green"></i>
                            </div>

                            <div class="w3-container w3-cell w3-center w3-cell-middle w3-quarter" style="font-weight: bold; color: darkgreen;">
                                <span class="w3-large"><?= $today_resolved ?></span>
                            </div>

                            <div class="w3-container w3-cell w3-cell-middle w3-half">
                                <span class="w3-large">Resolved Tickets</span>
                            </div>
                        </div>
                        <!-- End Message -->
                        <!-- Messages Content -->
                        <div class="w3-bar-item w3-hover-light-grey w3-cell-row" style="padding: 16px 24px;">
                            <div class="w3-container w3-cell w3-center w3-cell-middle w3-quarter">
                                <i class="fa-solid fa-reply-clock fa-xl w3-text-red"></i>
                            </div>

                            <div class="w3-container w3-cell w3-center w3-cell-middle w3-quarter" style="font-weight: bold; color: darkgreen;">
                                <span class="w3-large"><?= $hours ?>h <?= $minutes ?>m</span>
                            </div>

                            <div class="w3-container w3-cell w3-cell-middle w3-half">
                                <span class="w3-large">Response Time</span> <span>(avg)</span>
                            </div>
                        </div>
                        <!-- End Message -->
                    </div>
                    <!-- End Content -->
                </div>
            </div>


            <?php
            $sql = "SELECT requester_name, COUNT(*) AS count
                    FROM ticket
                    GROUP BY requester_name
                    ORDER BY count DESC
                    LIMIT 3;";
            $result = $conn->query($sql);

            // Fetch the results
            $top_users = array();
            while ($row = $result->fetch_assoc()) {
                $requester_name = $row['requester_name'];
                $count = $row['count'];
                $top_users[$requester_name] = $count;
            }

            $keys = array_keys($top_users);
            ?>

            <div class="w3-half w3-cell w3-container">
                <div class=" w3-card-2">
                    <!-- Module Header -->
                    <div class="w3-bar w3-container w3-text-white" style=" background-color: #0B293C; padding: 16px 40px;">
                        <h4>
                            <i class="fa-solid fa-face-smile-upside-down"></i> &nbsp;&nbsp;
                            Repeat Offenders
                        </h4>
                    </div>
                    <!-- End Header-->
                    <!-- Module Content -->
                    <div class="w3-bar-block w3-text-black w3-white">
                        <!-- Message Content -->
                        <div class="w3-bar-item w3-hover-light-grey w3-cell-row" style="padding: 16px 24px;">
                            <div class="w3-container w3-cell w3-center w3-cell-middle">
                                <i class="fa-solid fa-medal fa-2xl"></i><i class="fa-solid fa-circle-1"></i>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="w3-large" style="font-weight: bold;"><?= $keys[0] ?></span>
                                &nbsp;&nbsp;
                                <span class="w3-large"><?= $top_users[$keys[0]]; ?> <i class="fa-solid fa-ticket"></i></span>
                            </div>
                        </div>
                        <!-- End Message -->
                        <!-- Message Content -->
                        <div class="w3-bar-item w3-hover-light-grey w3-cell-row" style="padding: 16px 24px;">
                            <div class="w3-container w3-cell w3-center w3-cell-middle">
                                <i class="fa-solid fa-medal fa-2xl"></i><i class="fa-solid fa-circle-2"></i>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="w3-large" style="font-weight: bold;"><?= $keys[1] ?></span>
                                &nbsp;&nbsp;
                                <span class="w3-large"><?= $top_users[$keys[1]]; ?> <i class="fa-solid fa-ticket"></i></span>
                            </div>
                        </div>
                        <!-- End Message -->
                        <!-- Message Content -->
                        <div class="w3-bar-item w3-hover-light-grey w3-cell-row" style="padding: 16px 24px;">
                            <div class="w3-container w3-cell w3-center w3-cell-middle">
                                <i class="fa-solid fa-medal fa-2xl"></i><i class="fa-solid fa-circle-3"></i>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <span class="w3-large" style="font-weight: bold;"><?= $keys[2] ?></span>
                                &nbsp;&nbsp;
                                <span class="w3-large"><?= $top_users[$keys[2]]; ?> <i class="fa-solid fa-ticket"></i></span>
                            </div>
                        </div>
                        <!-- End Message -->
                    </div>
                    <!-- End Content -->
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








</body>
</html>