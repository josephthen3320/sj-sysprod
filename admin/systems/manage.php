<?php
session_start();

$page_title = "Manage Systems Status";

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <style>
        body {
            background-color: #fbfbfb
        }
        .jt-orange {
            background-color: #ff5722;
        }
        .fa-4xl {
            font-size: 3em;
            line-height: 0.01637em;
            vertical-align: -0.27679em;
        }
        select {
            font-family: FontAwesome, Roboto;
        }
        /* Styles for tooltip container */
        .tooltip-container {
            position: relative;
            display: inline-block;
        }

        /* Styles for tooltip speech bubble */
        .tooltip {
            position: absolute;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #000;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        /* Styles for tooltip arrow */
        .tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: #000 transparent transparent transparent;
        }

        /* Show tooltip on hover */
        .tooltip-container:hover .tooltip {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>
<body>

<!-- Left bar -->
<?= include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/sidebar.php" ; ?>

<div class="w3-threequarter w3-white" style="min-height: 100vh; margin-left: 25%; background-color: #fbfbfb">
    <?= include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div style="margin-top: 16px; min-height: 50vh;">
        <div id="ticketing" class="w3-bar w3-white w3-border-bottom" style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Systems Status</b></span>
            <br>
        </div>

        <div class="w3-container w3-padding-16" style="background-color: #fbfbfb; min-height: 100vh;">

            <?php
            $connSystems = getConnLog();
            $sqlMain = "SELECT * FROM service_main";
            $resultMain = $connSystems->query($sqlMain);

            while ($rowMain = $resultMain->fetch_assoc()) {
                echo "<h3>" . $rowMain['service_name'] . "</h3>";
                echo "<table class='w3-table-all'>";

                echo "<tr>";
                echo "<th>Service Name</th>";
                echo "<th class='w3-center'>Status</th>";
                echo "<th class='w3-center'>Manage</th>";
                echo "<th class='w3-center'>Last updated</th>";
                echo "</tr>";

                $sqlSub = "SELECT * FROM service_sub WHERE parent_id = '{$rowMain['id']}'";
                $resultSub = $connSystems->query($sqlSub);

                while ($rowSub = $resultSub->fetch_assoc()) {
                    echo "<tr>";

                    echo "<td>{$rowSub['subservice_name']}</td>";

                    echo "<td class='w3-center'>";
                    echo parseStatusIcon($rowSub['status']);
                    echo parseActionIcon($rowSub['action']);
                    echo "</td>";

                    echo "<td>";

                    // Status dropdown options
                    $statusOptions = [
                        1 => ['Online', 'w3-pale-green'],
                        2 => ['Affected', 'w3-pale-yellow'],
                        0 => ['Offline', 'w3-pale-red'],
                    ];

                    echo "<select class='w3-border w3-select w3-third {$statusOptions[$rowSub['status']][1]}' data-id='{$rowSub['id']}' onchange='updateStatus(this)'>";
                    foreach ($statusOptions as $value => $option) {
                        $selected = ($value == $rowSub['status']) ? "selected" : "";
                        echo "<option value='$value' $selected>{$option[0]}</option>";
                    }
                    echo "</select>";

                    // Action dropdown options
                    $actionOptions = [
                        0 => 'None',
                        1 => 'Maintenance',
                        2 => 'Emergency Maintenance',
                        3 => 'Investigating',
                        4 => 'Identified',
                        5 => 'Developing',
                    ];

                    echo "<select class='w3-border w3-select w3-third' data-id='{$rowSub['id']}' onchange='updateAction(this)'>";
                    foreach ($actionOptions as $value => $option) {
                        $selected = ($value == $rowSub['action']) ? "selected" : "";
                        echo "<option value='$value' $selected>$option</option>";
                    }
                    echo "</select>";

                    echo "</td>";


                    echo "<td>{$rowSub['last_updated']}</td>";

                    echo "</tr>";
                }

                echo "</table>";
            }
            ?>

            <script>
                // Function to update status using Ajax
                function updateStatus(selectElement) {
                    var serviceSubId = selectElement.getAttribute('data-id');
                    var newStatus = selectElement.value;

                    $.ajax({
                        type: 'POST',
                        url: 'update_status.php',
                        data: {
                            serviceSubId: serviceSubId,
                            newStatus: newStatus
                        },
                        success: function(response) {
                            // Handle the successful response here (if needed)
                            console.log(response); // Log the response for debugging
                        },
                        error: function(xhr, status, error) {
                            // Handle errors here (if any)
                            console.log(xhr.responseText); // Log the error response for debugging
                        }
                    });
                }

                // Function to update action using Ajax
                function updateAction(selectElement) {
                    var serviceSubId = selectElement.getAttribute('data-id');
                    var newAction = selectElement.value;

                    $.ajax({
                        type: 'POST',
                        url: 'update_action.php',
                        data: {
                            serviceSubId: serviceSubId,
                            newAction: newAction
                        },
                        success: function(response) {
                            // Handle the successful response here (if needed)
                            console.log(response); // Log the response for debugging
                        },
                        error: function(xhr, status, error) {
                            // Handle errors here (if any)
                            console.log(xhr.responseText); // Log the error response for debugging
                        }
                    });
                }
            </script>


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

<script>
    // Function to handle page refresh on select change with a delay
    function refreshPageOnSelectChange() {
        setTimeout(function() {
            location.reload(); // Reload the page after 0.5 seconds delay
        }, 500); // 500 milliseconds = 0.5 seconds
    }

    // Function to set up event listeners for all select elements
    function setupSelectEventListeners() {
        var selectElements = document.querySelectorAll('select');

        for (var i = 0; i < selectElements.length; i++) {
            selectElements[i].addEventListener('change', refreshPageOnSelectChange);
        }
    }

    // Call the setup function when the document is ready
    $(document).ready(function() {
        setupSelectEventListeners();
    });
</script>

</body>
</html>
<?php

function getNumberOfStatus($status) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";

    $connLog = getConnLog();
    $sql = "SELECT COUNT(*) AS count FROM service_sub WHERE status = '$status'";
    $result = $connLog->query($sql);
    $row = $result->fetch_assoc()['count'];
    return $row;
}

function parseStatusIcon($status) {

    switch ($status) {

        case 1:
            $iconClass = "circle";
            $iconColour = "green";
            break;

        case 2:
            $iconClass = "circle-half-stroke";
            $iconColour = "orange";
            break;

        case 0:
        default:
            $iconClass = "circle";
            $iconColour = "red";
            break;
    }


    return $statusIcon = "<i class='fas fa-fw fa-{$iconClass} w3-text-{$iconColour}'></i>";
}

function parseActionIcon($status) {

    switch ($status) {
        case 1:                         // Regular Maintenance
            $iconClass = "wrench";
            $iconColour = "blue";
            $actionName = "Maintenance";
            break;

        case 2:                         // Emergency maintenance
            $iconClass = "wrench";
            $iconColour = "deep-orange";
            $actionName = "Emergency Maintenance";

            break;

        case 3:                         // Investigating
            $iconClass = "magnifying-glass";
            $iconColour = "blue";
            $actionName = "Under investigation";

            break;

        case 4:                         // Identified
            $iconClass = "crosshairs";
            $iconColour = "indigo";
            $actionName = "Identified";

            break;

        case 5:                         // Identified
            $iconClass = "code-simple";
            $iconColour = "indigo";
            $actionName = "Under Development";

            break;

        case 0:     // no actions ongoing
        default:
            return null;
    }

    //$actionIcon = "<span class='w3-tooltip'><i class='fas fa-fw fa-{$iconClass} w3-text-{$iconColour}'></i><span></span></span>";

    $actionIcon = "<div class='tooltip-container'>";
    $actionIcon .= "<span class='w3-tooltip'>";
    $actionIcon .= "<i class='fas fa-fw fa-{$iconClass} w3-text-{$iconColour}'></i>";
    $actionIcon .= "<span class='tooltip w3-text w3-tag'>{$actionName}</span>";
    $actionIcon .= "</span>";
    $actionIcon .= "</div>";

    return $actionIcon;
}

?>