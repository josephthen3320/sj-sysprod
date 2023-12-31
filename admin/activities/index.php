<?php
session_start();
$uid = $_SESSION['user_id'];

$page_title = "User Activities";

// Check if the user is not logged in, redirect to login page
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";

$conn = getConnUser();
$log_conn = getConnLog();


require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_users.php";

$name = getUserFullnameByID($uid);

$top_title = "Activities Log";

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
        .jt-text-orange {
            color: #ff5722;
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

<div class="w3-threequarter sj-content w3-white" style="min-height: 100vh; margin-left: 25%; background-color: #fbfbfb">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div style="margin-top: 16px; min-height: 75vh;">
        <div id="modal-1" class="w3-bar w3-white w3-border-bottom" style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Activities Log</b></span>
        </div>

        <div class="w3-row">
            <div class="w3-col l8 m8 w3-margin-top">
                <iframe src="activity-log-table.php" width="100%" height="700px" frameborder="none"></iframe>
            </div>

            <div class="w3-row w3-col l4 m4 w3-margin-top">
                <div class="w3-col l12 m12">
                    <iframe src="insert-activity.php" width="100%" height="300px" frameborder="none"></iframe>
                </div>
                <div class="w3-col l12 m12">
                    <iframe src="activity-id-table.php" width="100%" height="400px" frameborder="none"></iframe>
                </div>
            </div>
        </div>


    </div>

    <script>
        function refreshIframes() {
            var iframe1 = document.getElementById("iframe1");
            iframe1.src = iframe1.src;

            var iframe2 = document.getElementById("iframe2");
            iframe2.src = iframe2.src;
        }

        document.getElementById("submit").onclick = function() {
            refreshIframes();
        };
    </script>



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