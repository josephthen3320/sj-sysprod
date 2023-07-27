<?php
session_start();
$uid = $_SESSION['user_id'];

$page_title = "Site Announcements";

// TODO: Change this to actual user role
$user_role = "Kucing Admin";

// Check if the user is not logged in, redirect to login page
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_users.php";

$name = getUserFullnameByID($uid);

$top_title = "Site Announcements";

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
        select {
            font-family: FontAwesome, Roboto;
        }
    </style>
</head>
<body>

<!-- Left bar -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/sidebar.php" ; ?>

<div class="w3-threequarter sj-content w3-white" style="min-height: 100vh; margin-left: 25%; background-color: #fbfbfb">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div style="margin-top: 16px; min-height: 50vh;">
        <div id="modal-1" class="w3-bar w3-white w3-border-bottom" style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Manage Announcements</b></span>
        </div>

        <div class="w3-padding-top-24">
            <div class="w3-half w3-cell w3-container">
                <div class=" w3-card-2">
                    <div class="w3-bar w3-container w3-text-white" style=" background-color: #0B293C; padding: 16px 40px;">
                        <h4>
                            <i class="fa-solid fa-messages"></i> &nbsp;&nbsp;
                            New Announcement
                        </h4>
                    </div>
                    <div class="w3-bar-block w3-text-black w3-white w3-container w3-padding">
                        <?php
                            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                                $connSubmit = getConnLog();

                                $subject = $_POST['subject'];
                                $description = $_POST['description'];
                                $type = $_POST['type'];

                                $sql = "INSERT INTO global_announcements (subject, details, type) VALUES ('$subject', '$description', '$type')";
                                $connSubmit->query($sql);
                                $connSubmit->close();

                                include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/agents/logging.php';
                                $details = "ANNOUNCEMENT CREATED; subject=" . $subject . ";";
                                logGeneric($uid, 25, $details);
                            }

                        ?>

                        <form action="" method="post">
                            <div class="w3-row w3-row-padding">

                                <div class="w3-col l6 m6 s12 w3-margin-top">
                                    <input class="w3-input w3-border" placeholder="Subject" name="subject" required>
                                </div>

                                <div class="w3-col l6 m6 s12 w3-margin-top">
                                    <select class="w3-select w3-border" name="type" required>
                                        <option value="0">&#xf05a; &nbsp; Normal</option>
                                        <option value="1">&#xf071; &nbsp; Critical</option>
                                        <option value="2">&#xf354; &nbsp; Service Down</option>
                                        <option value="5">&#xf357; &nbsp; Service Up</option>
                                        <option value="3">&#xf188; &nbsp; Bug</option>
                                        <option value="4">&#xf00c; &nbsp; Resolved</option>
                                        <option value="6">&#xf675; &nbsp; Notice</option>
                                    </select>
                                </div>

                                <div class="w3-col l12 m12 s12 w3-margin-top">
                                    <textarea class="w3-input w3-border" rows="5"  placeholder="Description" name="description" required></textarea>
                                </div>
                            </div>

                            <button class="w3-button w3-bar w3-padding-16 w3-blue-grey w3-padding w3-margin-top w3-margin-bottom">Announce</button>
                        </form>

                    </div>
                </div>
            </div>

            <div class="w3-half w3-cell w3-container">
                <div class=" w3-card-2">
                    <div class="w3-bar w3-container w3-text-white" style=" background-color: #0B293C; padding: 16px 40px;">
                        <h4>
                            <i class="fa-solid fa-messages"></i> &nbsp;&nbsp;
                            Latest Announcements
                        </h4>
                    </div>
                    <div class="w3-bar-block w3-text-black w3-white">
                        <iframe src="announcements-list.php" width="100%" height="300" frameborder="0"></iframe>
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
</body>
</html>