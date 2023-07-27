<?php
session_start();

$page_title = "Transaction";

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
    <script src="/assets/js/utils.js"></script>

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

        .w3-fifth {
            float:left;
            width: 50%;
        }

        @media (min-width:601px){
            .w3-fifth {
                width: 20%;
            }
        }
    </style>
</head>
<body>

<!-- Left bar -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/sidebar.php" ; ?>

<div class="w3-threequarter w3-white sj-content" style="min-height: 100vh; margin-left: 25%;">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div style="margin-top: 16px; min-height: 100vh; background-color: #fbfbfb;">
        <!-- Dashboard menu -->
        <div id="" class="w3-bar w3-white w3-border-bottom " style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Transaction</b></span>
        </div>

        <?php include $_SERVER['DOCUMENT_ROOT'] . "/transaction/nav-transaction.php" ?>

        <div class="w3-cell-row w3-container w3-padding-16">
            <div class="w3-col" style="margin-bottom: 32px;">
                <i class="fas fa-fw fa-arrow-up"></i> &nbsp; Silahkan pilih proses transaksi
            </div>
        </div>

        <div class="w3-container w3-cell-row w3-padding-16">
            <?php

                function getCountByPosition($positionId) {
                    $conn = getConnTransaction();

                    $sql = "SELECT COUNT(*) as count FROM position WHERE position_id = '$positionId'";
                    $result = $conn->query($sql);
                    return $result->fetch_assoc()['count'];
                }

                function getCountTransaction() {

                    $n = 0;

                    for ($i = 1; $i < 11; ++$i) {
                        $n += getCountByPosition($i);
                    }

                    return $n;
                }

            ?>


            <!-- Total Worksheet -->
            <div class="w3-third w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <i class="fa-solid fa-fw fa-file-lines fa-4xl" style="color: green;"></i><br><br>
                        <b><span class="w3-xlarge"><?= getCountByPosition(0) ?></span><br><span class="w3-text-grey">New Worksheet</span><br><br></b>
                    </div>
                </div>
            </div>

            <!-- Total Produksi -->
            <div class="w3-third w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <i class="fa-solid fa-fw fa-right-left fa-4xl" style="color: red;"></i><br><br>
                        <b><span class="w3-xlarge"><?= getCountTransaction() ?></span><br><span class="w3-text-grey">In Progress</span><br><br></b>
                    </div>
                </div>
            </div>

            <!-- Total Selesai -->
            <div class="w3-third w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <i class="fa-solid fa-fw fa-warehouse-full fa-4xl" style="color: darkblue;"></i><br><br>
                        <b><span class="w3-xlarge"><?= getCountByPosition(11) ?></span><br><span class="w3-text-grey">Gudang</span><br><br></b>
                    </div>
                </div>
            </div>

        </div>


        <div id="" class="w3-bar w3-white w3-border-bottom " style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Process Summary</b></span>
        </div>
        <!-- TRANSAKSI -->
        <div class="w3-container w3-cell-row w3-padding-16">

            <!-- Pola Marker -->
            <div class="w3-fifth w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button"
                     onclick="openURL('pola-marker')" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <i class="fa-solid fa-fw fa-draw-square fa-4xl" style="color: lightseagreen;"></i><br><br>
                        <b><span class="w3-xlarge"><?= getCountByPosition(1) ?></span><br><span class="w3-text-grey">Pola Marker</span><br><br></b>
                    </div>
                </div>
            </div>

            <!-- Cutting -->
            <div class="w3-fifth w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button"
                     onclick="openURL('cutting')" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <i class="fa-solid fa-fw fa-scissors fa-4xl" style="color: orangered;"></i><br><br>
                        <b><span class="w3-xlarge"><?= getCountByPosition(2) ?></span><br><span class="w3-text-grey">Cutting</span><br><br></b>
                    </div>
                </div>
            </div>

            <!-- Embro -->
            <div class="w3-fifth w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button"
                     onclick="openURL('embro')" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <i class="fa-solid fa-fw fa-scarf fa-4xl" style="color: purple;"></i><br><br>
                        <b><span class="w3-xlarge"><?= getCountByPosition(3) ?></span><br><span class="w3-text-grey">Embro</span><br><br></b>
                    </div>
                </div>
            </div>

            <!-- Print/Sablon -->
            <div class="w3-fifth w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button"
                     onclick="openURL('print-sablon')" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <i class="fa-solid fa-fw fa-pen-paintbrush fa-4xl" style="color: dodgerblue;"></i><br><br>
                        <b><span class="w3-xlarge"><?= getCountByPosition(4) ?></span><br><span class="w3-text-grey">Print/Sablon</span><br><br></b>
                    </div>
                </div>
            </div>

            <!-- QC Embro -->
            <div class="w3-fifth w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button"
                     onclick="openURL('qc-embro')" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <i class="fa-solid fa-fw fa-clipboard-list-check fa-4xl" style="color: teal;"></i><br><br>
                        <b><span class="w3-xlarge"><?= getCountByPosition(5) ?></span><br><span class="w3-text-grey">QC Embro</span><br><br></b>
                    </div>
                </div>
            </div>



            <!-- Sewing -->
            <div class="w3-fifth w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button"
                     onclick="openURL('sewing')" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <i class="fa-solid fa-fw fa-reel fa-4xl" style="color: deeppink;"></i><br><br>
                        <b><span class="w3-xlarge"><?= getCountByPosition(6) ?></span><br><span class="w3-text-grey">Sewing</span><br><br></b>
                    </div>
                </div>
            </div>

            <!-- Finishing -->
            <div class="w3-fifth w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button"
                     onclick="openURL('finishing')" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <i class="fa-solid fa-fw fa-list fa-4xl" style="color: darkgreen;"></i><br><br>
                        <b><span class="w3-xlarge"><?= getCountByPosition(8) ?></span><br><span class="w3-text-grey">Finishing</span><br><br></b>
                    </div>
                </div>
            </div>

            <!-- Washing -->
            <div class="w3-fifth w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button"
                     onclick="openURL('washing')" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <i class="fa-solid fa-fw fa-washing-machine fa-4xl" style="color: royalblue;"></i><br><br>
                        <b><span class="w3-xlarge"><?= getCountByPosition(7) ?></span><br><span class="w3-text-grey">Washing</span><br><br></b>
                    </div>
                </div>
            </div>

            <!-- QC Final -->
            <div class="w3-fifth w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button"
                     onclick="openURL('qc-final')" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <i class="fa-solid fa-fw fa-clipboard-check fa-4xl" style="color: lightseagreen;"></i><br><br>
                        <b><span class="w3-xlarge"><?= getCountByPosition(9) ?></span><br><span class="w3-text-grey">QC Final</span><br><br></b>
                    </div>
                </div>
            </div>

            <!-- Perbaikan -->
            <div class="w3-fifth w3-center w3-padding">
                <div class="w3-container w3-card-2 w3-display-container w3-white w3-round w3-button"
                     onclick="openURL('perbaikan')" style="height: 180px; width: 100%;">
                    <div class="w3-display-bottommiddle">
                        <i class="fa-solid fa-fw fa-screwdriver-wrench fa-4xl" style="color: sienna;"></i><br><br>
                        <b><span class="w3-xlarge"><?= getCountByPosition(10) ?></span><br><span class="w3-text-grey">Perbaikan</span><br><br></b>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/bottombar.php" ?>

</body>
</html>

<script>
    function dropdown(id) {
        var x = document.getElementById(id);
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }
</script>