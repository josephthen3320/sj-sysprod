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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Cutting Overview</b></span>
        </div>

        <div class="w3-row w3-padding">

            <div class="w3-col l4 m6 s12 w3-padding">
                <?php

                include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
                $conn = getConnTransaction();

                // Calculate the date 7 days ago
                $startDate = date('Y-m-d', strtotime('-14 days'));

                // Fetch data from the database for the past 7 days
                $sql = "SELECT DATE(date_in) AS date, COUNT(*) AS count FROM cutting WHERE date_in >= '$startDate' GROUP BY DATE(date_in) ORDER BY DATE(date_in)";
                $result = $conn->query($sql);

                // Prepare data for the chart
                $dates = [];
                $counts = [];

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $dates[] = $row["date"];
                        $counts[] = $row["count"];
                    }
                }

                ?>


                <canvas id="myChart"></canvas>

                <script>
                    // Chart configuration
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode($dates); ?>,
                            datasets: [{
                                label: 'Cutting Start',
                                data: <?php echo json_encode($counts); ?>,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    precision: 0
                                }
                            }
                        }
                    });
                </script>

            </div>

            <div class="w3-col l8 m6 s12 w3-red">
                ouch
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