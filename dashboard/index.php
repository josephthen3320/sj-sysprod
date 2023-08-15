<?php
session_start();

$page_title = "Dashboard";

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
    <title><?= $page_title ?></title>

    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/favicon/site.webmanifest">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/css/sj-theme.css">
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

        .fl-talenta {
            display: inline-block;
            width: 56px;
            height: 56px;
            background-image: url('data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56" fill="none"><path d="M11.3844 53.1247L43.6435 41.9834C49.4813 40.1311 53.6668 35.3563 53.6668 29.1682C53.6668 22.9801 49.5363 18.3013 43.8225 16.3941L11.3844 5.21171C9.92986 4.58432 8.29824 4.49531 6.78362 4.96071C5.269 5.4261 3.97098 6.4153 3.12348 7.75005C1.48505 10.6177 2.51768 13.9107 5.09234 16.1334L20.0172 29.1682L5.09234 42.2029C2.54521 44.4257 1.51259 47.6912 3.16478 50.5863C4.00497 51.9179 5.29561 52.9058 6.80325 53.3714C8.31088 53.8369 9.93594 53.7494 11.3844 53.1247Z" fill="url(%23paint0_linear)"/><path d="M10.6836 37.3044L19.991 29.168L36.5267 44.3294L10.6836 37.3044Z" fill="url(%23paint1_linear)"/><defs><linearGradient id="paint0_linear" x1="-3.08601" y1="18.4934" x2="45.5143" y2="46.6567" gradientUnits="userSpaceOnUse"><stop stop-color="%23C02A34"/><stop offset="1" stop-color="%23FF6464"/></linearGradient><linearGradient id="paint1_linear" x1="24.2112" y1="29.4309" x2="24.2114" y2="44.5168" gradientUnits="userSpaceOnUse"><stop stop-color="%23C02A34"/><stop offset="0.2265" stop-color="%23C62F38" stop-opacity="0.7735"/><stop offset="0.5227" stop-color="%23D63E45" stop-opacity="0.4773"/><stop offset="0.8564" stop-color="%23F15759" stop-opacity="0.1436"/><stop offset="1" stop-color="%23FF6464" stop-opacity="0"/></linearGradient></defs></svg>');
            background-repeat: no-repeat;
            background-size: contain;
        }

        .fl-accurate {
            width: 56px;
            height: 56px;
            background-image: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='56' height='56' viewBox='0 0 56 56' fill='none'><path d='M37.282 14.4152C36.4893 10.8483 34.3601 7.72071 31.3321 5.67565C28.3041 3.63058 24.6075 2.8235 21.0027 3.42037C17.3979 4.01724 14.1588 5.9727 11.9516 8.88459C9.74439 11.7965 8.73683 15.4434 9.13614 19.0754C9.53545 22.7075 11.3113 26.0484 14.0984 28.4112C16.8856 30.774 20.4721 31.979 24.1205 31.7784C27.7689 31.5779 31.2017 29.987 33.7131 27.3329C36.2244 24.6788 37.6233 21.1633 37.6221 17.5095C37.6216 16.4687 37.5076 15.4311 37.282 14.4152ZM28.0541 25.0638L24.5921 15.4458C23.8917 13.5021 22.7487 13.5021 22.0482 15.4458L18.5862 25.0638C16.7027 24.8337 15.7721 23.3778 16.5075 21.6293L21.8605 8.90198C22.6634 6.99338 23.9768 6.99338 24.7797 8.90198L30.1327 21.6293C30.8682 23.3803 29.9401 24.8337 28.0541 25.0638Z' fill='%23991B26'/></svg>");
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body>

<!-- Left bar -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/sidebar.php" ; ?>

<div class="w3-threequarter w3-white sj-content" style="min-height: 100vh; margin-left: 25%; padding-bottom: 64px;">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div class="w3-hide-small w3-hide-medium" style="margin-top: 16px; min-height: 100vh; background-color: #fbfbfb">
        <!-- Dashboard menu -->
        <div id="user-management" class="w3-bar w3-white w3-border-bottom " style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b><?= $page_title ?></b></span>
        </div>

        <div class="w3-container w3-cell-row w3-padding-16">

            <!-- Talenta Button -->
            <div class="w3-col l3 m3 s6 w3-padding">
                <div class="w3-button w3-white w3-card-2 w3-border w3-padding-top-24 w3-col l12 m12 s12" onclick="openTabURL('https://hr.talenta.co/', 'quicklinks')">
                    <img src="/assets/logo/logo_talenta.webp" height="50px" style="margin-bottom: 15px;">
                    <br>
                    <span class="w3-large" style="font-weight: bold;">Talenta</span>
                </div>
            </div>

            <!-- Talenta Button -->
            <div class="w3-col l3 m3 s6 w3-padding">
                <div class="w3-button w3-white w3-card-2 w3-border w3-padding-top-24 w3-col l12 m12 s12" onclick="openTabURL('https://public.accurate.id/', 'quicklinks')">
                    <img src="/assets/logo/logo_accurate.png" height="50px" style="margin-bottom: 15px;">
                    <br>
                    <span class="w3-large" style="font-weight: bold;">Accurate</span>
                </div>
            </div>

            <!-- Sysprod Button -->
            <div class="w3-col l3 m3 s6 w3-padding">
                <div class="w3-button w3-white w3-card-2 w3-border w3-padding-top-24 w3-col l12 m12 s12" onclick="openURL('http://<?= $_SERVER['HTTP_HOST'] ?>/production')">
                    <img src="/assets/logo/sysprod_logo_i.png" height="50px" style="margin-bottom: 15px;">
                    <br>
                    <span class="w3-large" style="font-weight: bold;">SysProd</span>
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
                        <iframe src="dailies.php" width="100%" height="300"></iframe>
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
                        <iframe src="announcements.php" width="100%" height="300"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="w3-container w3-cell-row w3-padding-16">
            <div class="w3-row w3-container">
                <div class="w3-card-2">
                    <div class="w3-bar w3-container w3-text-white" style=" background-color: #0B293C; padding: 16px 40px;">
                        <h4>
                            <i class="fa-solid fa-messages"></i> &nbsp;&nbsp;
                            Service Status Monitor
                        </h4>
                    </div>
                    <div class="w3-bar-block w3-text-black w3-white">
                        <iframe src="service-tracker.php" width="100%" height="500"></iframe>
                    </div>
                </div>
            </div>

        </div>

    </div>


    <div class="w3-hide-large">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/dashboard/mobile-dashboard.php'; ?>
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


<?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/bottombar.php" ?>


</body>
</html>