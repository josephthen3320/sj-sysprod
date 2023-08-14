<?php
session_start();

$page_title = "Reporting";

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

<body>

<!-- Left bar -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/sidebar.php" ; ?>

<div class="w3-threequarter w3-white sj-content" style="min-height: 80vh; margin-left: 25%;">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div style="margin-top: 16px; min-height: 95vh; background-color: #fbfbfb;">
        <!-- Dashboard menu -->
        <div id="" class="w3-bar w3-white w3-border-bottom " style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Reporting</b></span>
        </div>



        <div class="w3-container w3-padding-top-24">

            <!-- todo: remove once everything is up -->
            <span class=""><i class="fas fa-fw w3-text-indigo fa-square"></i> &nbsp; Menu tersedia </span><br>
            <span class=""><i class="fas fa-fw w3-text-black fa-square"></i> &nbsp; Menu belum tersedia </span>

            <div style="margin-bottom: 48px;">
                <h3>Laporan Cutting</h3>

                <div class="w3-cell-row">
                    <div class="w3-col l3 m3 s6 w3-padding">
                        <div class="w3-button w3-white w3-card-2 w3-border w3-padding-top-24 w3-col l12 m12 s12" onclick="openURL('cutting/hasil-cutting.php')">
                            <span class="fa-stack" style="vertical-align: top;">
                                <i class="w3-text-indigo fas fa-fw fa-scissors fa-stack-2x"></i>
                                <i class="w3-text-red fas fa-fw fa-right fa-stack-1x" style="left:65%"></i>
                            </span><br><br>
                            <span>Hasil Cutting</span>
                        </div>
                    </div>

                    <div class="w3-col l3 m3 s6 w3-padding">
                        <div class="w3-button w3-white w3-card-2 w3-border w3-padding-top-24 w3-col l12 m12 s12" onclick="openURL('cutting/pengiriman-cutting-sewing.php')">
                            <span class="fa-stack" style="vertical-align: top;">
                                <i class="w3-text-indigo fas fa-fw fa-truck fa-stack-2x"></i>
                                <i class="w3-text-red fas fa-fw fa-reel fa-stack-1x" style="left:65%; top:-50%;"></i>
                            </span><br><br>
                            <span>Pengiriman Hasil Cutting ke Sewing</span>
                        </div>
                    </div>

                    <div class="w3-col l3 m3 s6 w3-padding">
                        <div class="w3-button w3-white w3-card-2 w3-border w3-padding-top-24 w3-col l12 m12 s12" onclick="openURL('cutting/pengiriman-cutting-embro.php')">
                            <span class="fa-stack" style="vertical-align: top;">
                                <i class="w3-text-indigo fas fa-fw fa-truck fa-stack-2x"></i>
                                <i class="w3-text-red fas fa-fw fa-scarf fa-stack-1x" style="left:65%; top:-50%;"></i>
                            </span><br><br>
                            <span>Pengiriman Hasil Cutting ke Embro</span>
                        </div>
                    </div>

                    <div class="w3-col l3 m3 s6 w3-padding">
                        <div class="w3-button w3-white w3-card-2 w3-border w3-padding-top-24 w3-col l12 m12 s12" onclick="openURL('cutting/pengiriman-cutting-sablon.php')">
                            <span class="fa-stack" style="vertical-align: top;">
                                <i class="w3-text-indigo fas fa-fw fa-truck fa-stack-2x"></i>
                                <i class="w3-text-red fas fa-fw fa-pen-paintbrush fa-stack-1x" style="left:65%; top:-50%;"></i>
                            </span><br><br>
                            <span>Pengiriman Hasil Cutting ke Print/Sablon</span>
                        </div>
                    </div>
                </div>
            </div>


            <div style="margin-bottom: 48px;">
                <h3>Laporan Sewing (CMT)</h3>

                <div class="w3-cell-row">

                    <div class="w3-col l3 m3 s6 w3-padding">
                        <div class="w3-button w3-white w3-card-2 w3-border w3-padding-top-24 w3-col l12 m12 s12" onclick="openURL('sewing/penerimaan-sewing.php')">
                            <span class="fa-stack" style="vertical-align: top;">
                                <i class="w3-text-indigo fas fa-fw fa-hand-holding fa-stack-2x" style="top:5%"></i>
                                <i class="w3-text-pink fas fa-fw fa-reel fa-stack-1x" style="top: -20%;"></i>
                            </span><br><br>
                            <span>Penerimaan Hasil Sewing</span>
                        </div>
                    </div>

                    <div class="w3-col l3 m3 s6 w3-padding">
                        <div class="w3-button w3-white w3-card-2 w3-border w3-padding-top-24 w3-col l12 m12 s12" onclick="openURL('sewing/cmt-detail.php')">
                            <span class="fa-stack" style="vertical-align: top;">
                                <i class="w3-text-indigo fas fa-fw fa-warehouse fa-stack-2x"></i>
                                <i class="fas fa-fw fa-exclamation-circle fa-stack-1x w3-text-pink" style="left:75%; top: -25%;"></i>
                            </span><br><br>
                            <span>Sisa Barang di CMT</span>
                        </div>
                    </div>
                </div>
            </div>


            <div style="margin-bottom: 48px;">
                <h3>Laporan Finishing & QC Final</h3>

                <div class="w3-cell-row">

                    <div class="w3-col l3 m3 s6 w3-padding">
                        <div class="w3-button w3-white w3-card-2 w3-border w3-padding-top-24 w3-col l12 m12 s12" onclick="openURL('sewing/penerimaan-sewing.php')">
                            <span class="fa-stack" style="vertical-align: top;">
                                <i class="w3-text-meong fas fa-fw fa-hand-holding fa-stack-2x" style="top:5%"></i>
                                <i class="w3-text-pink fas fa-fw fa-reel fa-stack-1x" style="top: -20%;"></i>
                            </span><br><br>
                            <span>Status Finishing</span>
                        </div>
                    </div>


                    <div class="w3-col l3 m3 s6 w3-padding">
                        <div class="w3-button w3-white w3-card-2 w3-border w3-padding-top-24 w3-col l12 m12 s12">
                            <span class="fa-stack" style="vertical-align: top;">
                                <i class="w3-text-meong fas fa-fw fa-file-lines fa-stack-2x"></i>
                                <i class="w3-text-pink fas fa-fw fa-right-to-line fa-stack-1x" style="left:-55%"></i>
                            </span><br><br>
                            <span>Penerimaan QC Final</span>
                        </div>
                    </div>
                </div>
            </div>


            <div style="margin-bottom: 48px;">
                <h3>Laporan Gudang</h3>

                <div class="w3-cell-row">
                    <div class="w3-col l3 m3 s6 w3-padding">
                        <div class="w3-button w3-white w3-card-2 w3-border w3-padding-top-24 w3-col l12 m12 s12">
                            <span class="fa-stack" style="vertical-align: top;">
                                <i class="fas fa-fw fa-file-lines fa-stack-2x"></i>
                                <i class="fas fa-fw fa-right-to-line fa-stack-1x" style="left:-55%"></i>
                            </span><br><br>
                            <span>Penerimaan QC Final</span>
                        </div>
                    </div>

                    <div class="w3-col l3 m3 s6 w3-padding">
                        <div class="w3-button w3-white w3-card-2 w3-border w3-padding-top-24 w3-col l12 m12 s12">
                            <span class="fa-stack" style="vertical-align: top;">
                                <i class="fas fa-fw fa-warehouse-full fa-stack-2x"></i>
                                <i class="fas fa-fw fa-right-to-line fa-stack-1x" style="left:-75%"></i>
                            </span><br><br>
                            <span>Masuk Gudang</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/bottombar.php" ?>

</body>
</html>

