<?php
session_start();

$page_title = "Cutting | Transaction";

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

        #loading {
            display: none;
            width: 100%;
            height: 500px;
            background-color: #f2f2f2;
            text-align: center;
            line-height: 500px;
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Left bar -->
<?= include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/sidebar.php" ; ?>

<div class="w3-threequarter w3-white" style="min-height: 100vh; margin-left: 25%;">
    <?= include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div style="margin-top: 16px; min-height: 100vh; background-color: #fbfbfb;">
        <!-- Dashboard menu -->
        <div id="" class="w3-bar w3-white w3-border-bottom " style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Cutting</b></span>
        </div>

        <?php include $_SERVER['DOCUMENT_ROOT'] . "/transaction/nav-transaction.php" ?>

        <div class="w3-bar w3-padding">
            <button class="w3-bar-item w3-button w3-blue-grey w3-padding-16 w3-margin-right" onclick="changeIframeSrc('table-cutting.php')">
                <i class="fas fa-table"></i> &nbsp; View Table
            </button>
            <button class="w3-bar-item w3-button w3-blue-grey w3-padding-16 w3-margin-right" onclick="changeIframeSrc('report.php')">
                <i class="fas fa-file-lines"></i> &nbsp; View Report
            </button>
            <button class="w3-bar-item w3-button w3-blue-grey w3-padding-16 w3-margin-right" onclick="openPopup('print-report.php', 'printReport')">
                <i class="fas fa-print"></i> &nbsp; Print Report
            </button>
            <button class="w3-bar-item w3-button w3-pale-green w3-padding-16 w3-margin-right" onclick="changeIframeSrc('report-send-embro.php')">
                <i class="fas fa-print"></i> &nbsp; Rekap kirim ke Embro
            </button>
        </div>


        <!-- Data Table -->
        <div class="w3-cell-row w3-padding">
            <div id="loading">Loading...</div>
            <iframe id="pmFrame" src="table-cutting.php" width="100%" frameborder="0" style="min-height: 75vh;"></iframe>
        </div>

        <script>
            var iframe = document.getElementById('pmFrame');
            var loading = document.getElementById('loading');

            function changeIframeSrc(src) {
                showLoadingAnimation();
                iframe.src = src;

                // Store the selected URL in localStorage
                localStorage.setItem('selectedURL', src);
            }

            iframe.addEventListener('load', hideLoadingAnimation);

            function showLoadingAnimation() {
                loading.style.display = 'block';
            }

            function hideLoadingAnimation() {
                loading.style.display = 'none';
            }



            // Get all iframe elements
            var iframes = document.querySelectorAll('iframe');

            // Adjust the height for each iframe
            for (var i = 0; i < iframes.length; i++) {
                var iframe = iframes[i];

                // Set the height when the iframe content loads
                iframe.onload = function() {
                    // Set the iframe height to the content's height
                    this.style.height = this.contentWindow.document.body.scrollHeight + 'px';
                };
            }
        </script>

        <div class="w3-container w3-cell-row w3-padding-16">

        </div>

    </div>
</div>

<script>
    function openPopup(url, name) {
        var windowFeatures = "width=794,height=1123,top=100,left=200,resizable=no,scrollbars=no,toolbar=no,menubar=no,location=no,status=no";

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