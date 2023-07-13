<?php
session_start();

$page_title = "User Management";

// TODO: Change this to actual user role
$user_role = "Kucing Admin";

// Check if the user is not logged in, redirect to login page
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_users.php";

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
    <title>User Table | <?= $top_title ?></title>
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
        <div id="ticketing" class="w3-bar w3-white w3-border-bottom" style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>User Table</b></span>
        </div>

        <div class="w3-container w3-padding-16" style="background-color: #fbfbfb">
            <table class="w3-table w3-table-all">
                <tr class="w3-small">
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Create Date</th>
                    <th>Actions</th>
                </tr>

                <?php
                // Include the database connection file
                require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';

                // Fetch all user information from the database
                $sql = "SELECT *
                        FROM users
                        INNER JOIN user_accounts ON users.username = user_accounts.username
                        WHERE user_accounts.id > 0";

                $result = mysqli_query($conn, $sql);

                // Check if any tickets are available
                if (mysqli_num_rows($result) > 0) {
                    // Display the tickets in a table
                    // Loop through each ticket and display its details
                    while ($row = mysqli_fetch_assoc($result)) {
                        $user_cur = $row['username'];

                        $userdata = getUserDataByUsername($user_cur);

                        $status_icons = "";

                        $status_icons .= ($row['is_locked']) ? "&nbsp;&nbsp;<i class='fa-solid fa-lock fa-2xs'></i>" : "";

                        $sql_checkpasstoken = "SELECT id FROM password_reset_token WHERE username = '$user_cur' AND is_expired = 0";
                        $result_token = $conn->query($sql_checkpasstoken);
                        if ($result_token->num_rows > 0) {
                            $status_icons .= "&nbsp;&nbsp;<i class='fa-solid fa-key fa-2xs'></i>";
                        }


                        echo "<tr class='w3-hover-blue-grey' style='cursor: pointer;'>";
                        echo "<td>#{$userdata['id']}</td>";
                        echo "<td>                        
                                <img class=\"w3-circle\" src=\"/img/profile-placeholder.png\" width=\"40px\"> &nbsp;&nbsp;
                                {$userdata['first_name']} {$userdata['last_name']}</td>";
                        echo "<td>{$userdata['username']}{$status_icons}</td>";
                        echo "<td>{$userdata['email']}</td>";
                        echo "<td>{$userdata['date_created']}</td>";
                        echo "<td>
                                <span class='w3-button w3-round-large w3-blue-grey' onclick='resetPopup(\"{$userdata['username']}\")'><i class='fa-solid fa-shield-keyhole'></i>&nbsp;&nbsp;Reset</span>&nbsp;";

                        if (!$userdata['is_locked']){
                            echo "<span class='w3-button w3-round-large w3-blue-grey' onclick='lockAccount(\"{$userdata['username']}\")'><i class='fa-solid fa-lock'></i>&nbsp;&nbsp;Disable</span>";
                        } else {
                            echo "<span class='w3-button w3-round-large w3-blue-grey' onclick='unlockAccount(\"{$userdata['username']}\")'><i class='fa-solid fa-lock-open'></i>&nbsp;&nbsp;Enable</span>";
                        }




                        echo "</td>";
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