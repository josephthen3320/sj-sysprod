<?php
session_start();

// TODO: Change this to actual user role
$user_role = "Kucing Admin";

// Check if the user is not logged in, redirect to login page
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnUser();
$log_conn = getConnLog();

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
    <title>Activity Log <?= $top_title ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/w3.css">
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
<?= include "../modular/sidebar.php"; ?>

<div class="w3-threequarter w3-white" style="min-height: 100vh; margin-left: 25%;">
    <?= include "../modular/topbar.php"; ?>

    <div style="margin-top: 16px; min-height: 100vh;">
        <!-- Ticketing Menu -->
        <div id="ticketing" class="w3-bar w3-white w3-border-bottom" style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>User Table</b></span>
        </div>

        <div class="w3-container w3-padding-16" style="background-color: #fbfbfb">

            <div class="w3-col">
                <table class="w3-table w3-table-all">
                    <tr class="w3-small">
                        <th class="w3-center">Activity ID</th>
                        <th class="w3-center">User</th>
                        <th class="w3-center">Type</th>
                        <th class="w3-center">Details</th>
                        <th class="w3-center">Timestamp</th>
                    </tr>

                    <?php
                    // Include the database connection file
                    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/logging/get_user_information.php';

                    // Fetch all user information from the database
                    $sql = "SELECT *
                        FROM user_activity_log ORDER BY timestamp DESC";

                    $result = mysqli_query($log_conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {

                            $aid = str_pad($row['id'], 4, '0', STR_PAD_LEFT);
                            $user_fname = getUserFullnameByUsername(getUsernameById($row['user_id']));
                            $activity_name = getActivityName($row['activity_id'], $log_conn);
                            $activity_name = $activity_name == "" ? "???" : $activity_name;
                            //$activity_detail = $row['activity_detail'];
                            $activity_detail = implode("<br>", explode(";;", $row['activity_detail']));

                            echo "<tr class='w3-small' style='cursor: ;'>";
                            echo "<td class='w3-center' style='vertical-align: middle'>{$aid}</td>";
                            echo "<td style='vertical-align: middle' class='''>{$user_fname}</td>";
                            echo "<td style='vertical-align: middle' class='w3-center'>{$activity_name}</td>";
                            echo "<td style='vertical-align: middle; width: 35%;' class=''>{$activity_detail}</td>";
                            echo "<td style='vertical-align: middle' class=''>{$row['timestamp']}</td>";
                            echo "</tr>";
                        }
                    }

                    $conn->close();


                    function getActivityName($a_id, $log_conn) {
                        $a_sql = "SELECT activity FROM activities WHERE id = '$a_id'";
                        $a_result = $log_conn->query($a_sql);

                        if ($a_result->num_rows > 0) {
                            $a_name = $a_result->fetch_assoc()['activity'];
                        }
                        else {
                            $a_name = "";
                        }


                        return $a_name;
                    }
                    ?>

                </table>
            </div>


        </div>
    </div>
</div>

</body>
</html>