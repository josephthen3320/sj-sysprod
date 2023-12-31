<?php
session_start();

$page_title = "Pola Marker | Transaction";

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
<?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/sidebar.php" ; ?>

<div class="w3-threequarter w3-white sj-content" style="min-height: 100vh; margin-left: 25%; margin-bottom: 64px;">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div style="margin-top: 16px; min-height: 100vh; background-color: #fbfbfb;">
        <!-- Dashboard menu -->
        <div id="" class="w3-bar w3-white w3-border-bottom " style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Pola Marker</b></span>
        </div>

        <?php include $_SERVER['DOCUMENT_ROOT'] . "/transaction/nav-transaction.php" ?>

        <div class="w3-padding-24 w3-light-blue w3-border w3-container">
            <form method="post" action="pola_sj.php" class="w3-row w3-small" target="_blank">
                <h6 class="w3-col l3">Print Surat Jalan Harian</h6>
                <input type="date" name="fDate" id="fDate" class="w3-input w3-col l2" value="<?= date('Y-m-d') ?>">
                <select name="fLocation" class="w3-select w3-col l2">
                    <option selected value="">Semua Lokasi</option>
                    <?php
                    $sql = "SELECT * FROM cmt WHERE cmt_type = 'CT1'";
                    $pConn = getConnProduction();

                    $result = $pConn->query($sql);

                    while ($fLoc = $result->fetch_assoc()) {
                        echo "<option value='{$fLoc['cmt_id']}'>{$fLoc['cmt_name']}</option>";
                    }
                    ?>
                </select>
                <input hidden value="LX" name="printType">
                <button type="submit" class="w3-button w3-indigo w3-col l2">Print</button>
            </form>
        </div>

        <div class="w3-cell-row w3-padding">
            <div id="loading">Loading...</div>
            <iframe id="pmFrame" src="table-pola-marker.php" width="100%" frameborder="0" style="min-height: 75vh;"></iframe>
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
        </script>

        <div class="w3-container w3-cell-row w3-padding-16">

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

<?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/bottombar.php" ?>

</body>
</html>