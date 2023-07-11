<?php
session_start();

$page_title = "Role Management";

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
<?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/sidebar.php" ; ?>

<div class="w3-threequarter w3-white sj-content" style="min-height: 100vh; margin-left: 25%;">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div style="margin-top: 16px; min-height: 100vh;">
        <!-- Ticketing Menu -->
        <div id="" class="w3-bar w3-white w3-border-bottom" style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Role Table</b></span>
        </div>
        <div id="" class="w3-bar w3-black w3-border-bottom">
            <button class="w3-bar-item w3-button" onclick="openPopup('create-role.php')">Add Role &nbsp; <i class="fa-solid fa-plus fa-sm"></i></button>
        </div>

        <div class="w3-container w3-padding-16" style="background-color: #fbfbfb">
            <table class="w3-table w3-table-all">
                <tr class="w3-small">
                    <th class="w3-center">ID</th>
                    <th class="w3-center">Actions</th>
                    <th class="w3-center">Role</th>
                    <th class="w3-center">Description</th>
                    <th class="">Created on</th>
                    <th class="">Last modified</th>
                </tr>

                <?php
                // Include the database connection file
                require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';

                // Fetch all user information from the database
                $sql = "SELECT *
                        FROM roles WHERE id >= 0";

                $result = mysqli_query($conn, $sql);

                // Check if any tickets are available
                if (mysqli_num_rows($result) > 0) {
                    // Display the tickets in a table
                    // Loop through each ticket and display its details
                    while ($row = mysqli_fetch_assoc($result)) {

                        $create_date = $row['created_at']; // date("Y-m-d", strtotime($row['created_at']));
                        $modified_date = date("Y-m-d H:i:s", strtotime($row['last_modified_at']));
                        $modified_user = $row['last_modified_by']; $created_user = $row['created_by'];

                        //$modified_user_sql = "SELECT username FROM user_accounts"; // .  WHERE id = $modified_user";

                        $modified_user_sql = "SELECT first_name, last_name 
                                      FROM users 
                                      INNER JOIN user_accounts ON users.username = user_accounts.username
                                      WHERE id = $modified_user";

                        $user_result = $conn->query($modified_user_sql);
                        $user_row = $user_result->fetch_assoc();
                        $modifier_fullname = $user_row['first_name'] . " " . $user_row['last_name'];

                        $created_user_sql = "SELECT first_name, last_name 
                                      FROM users 
                                      INNER JOIN user_accounts ON users.username = user_accounts.username
                                      WHERE id = $created_user";

                        $user_result = $conn->query($created_user_sql);
                        $user_row = $user_result->fetch_assoc();
                        $creator_fullname = $user_row['first_name'] . " " . $user_row['last_name'];


                        /* $user_result = $conn->query($created_user_sql);
                         $user_row = $result->fetch_assoc();
                         $creator_fullname = $user_row['first_name'] . " " . $user_row['last_name'];*/

                        echo "<tr class='' style='cursor: ;'>";
                        echo "<td style='vertical-align: middle'>#{$row['id']}</td>";
                        echo "<td class='w3-center' style='vertical-align: middle'>
                                <span class='w3-hover-text-blue' style='cursor: pointer;'><i class='fa-solid fa-edit'></i></span>&nbsp;&nbsp;
                                <span class='w3-hover-text-blue' style='cursor: pointer;'><i class='fa-solid fa-trash-alt'></i></span>
                              </td>";
                        echo "<td style='vertical-align: middle'>{$row['role_name']}</td>";
                        echo "<td style='vertical-align: middle'>{$row['description']}</td>";
                        echo "<td style='vertical-align: middle'>" . calcDays($create_date) . "<br><span class='w3-small'>by {$modifier_fullname}</span>";
                        echo "<td style='vertical-align: middle'>" . calcDays($modified_date) . "<br><span class='w3-small'>by {$creator_fullname}</span></td>";
                        echo "</tr>";
                    }
                }

                $conn->close();
                ?>

            </table>


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