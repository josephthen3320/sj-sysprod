<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sysprod System Status</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>
<style>
    .theme-dark {
        background-color: #0B293C;
    }


    .content-padding {
        padding-left: 5vw;
        padding-right: 5vw;
    }

    .circle {
        width: 100%; /* Adjust the width and height as desired */
        aspect-ratio: 1/1;
        border-radius: 50%; /* Set the border-radius to 50% to create a circle */
    }
    /* Scroll behavior */
    html {
        scroll-behavior: smooth;
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

<body class="" style="padding-top: 32px; padding-bottom: 32px;">

<div class="w3-bar content-padding">
    <div class="w3-row">
        <div class="w3-col w3-row l6 m6 s12" style="padding-right: 8px;">
            <?php
            $online = getNumberOfStatus(1);
            $affected = getNumberOfStatus(2);
            $offline = getNumberOfStatus(0);
            ?>
            <div class="w3-col l4 m4 s4 w3-padding w3-large w3-center">
                <div class="circle w3-display-container w3-green w3-container">
                    <span class="w3-xxlarge w3-display-middle"><?= $online ?></span>
                </div>
                <div class="w3-large w3-center" style="padding-top: 16px">
                    ONLINE
                </div>
            </div>
            <div class="w3-col l4 m4 s4 w3-padding w3-padding w3-large">
                <div class="circle w3-display-container w3-orange w3-container">
                    <span class="w3-xxlarge w3-display-middle"><?= $affected ?></span>
                </div>
                <div class="w3-large w3-center" style="padding-top: 16px">
                    AFFECTED
                </div>
            </div>
            <div class="w3-col l4 m4 s4 w3-padding w3-padding w3-large">
                <div class="circle w3-display-container w3-grey w3-container">
                    <span class="w3-xxlarge w3-display-middle"><?= $offline ?></span>
                </div>
                <div class="w3-large w3-center" style="padding-top: 16px">
                    OFFLINE
                </div>
            </div>

            <div class="w3-col l12 m12 s12 w3-center" style="padding-top: 16px">
                Informasi tentang ketersediaan layanan IT inti CV. Subur Jaya.
                <br>
                Untuk melaporkan masalah, silahkan hubungi ITS.
            </div>
        </div>

        <div class="w3-col w3-row l6 m6 s12 w3-right-align" style="padding-left: 8px;">
            <table class="w3-table-all" style="width: 100%;">
                <thead>
                <tr class="w3-small">
                    <th>SERVICE NAME</th>
                    <th class="w3-center">STATUS</th>
                </tr>
                </thead>
                <tbody>
                <?php
                require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
                $connLog = getConnLog();

                $sql = "SELECT * FROM service_main";
                $result = $connLog->query($sql);

                while ($row = $result->fetch_assoc()) {

                    $statusIcon = parseStatusIcon($row['service_status']);
                    $actionIcon = parseActionIcon($row['status_action']);

                    echo "<tr>";

                    echo "<td>{$row['service_name']}</td>";
                    echo "<td class=''>";

                    $sql2 = "SELECT * FROM service_sub WHERE parent_id = '{$row['id']}'";
                    $result2 = $connLog->query($sql2);

                    while ($row2 = $result2->fetch_assoc()) {
                        echo "<div class='tooltip-container'>";
                        echo "<a href='#{$row2['subservice_name']}' class='w3-tooltip'>";
                        echo parseStatusIcon($row2['status']);
                        echo "<span class='tooltip w3-text w3-tag'>{$row2['subservice_name']}</span>";
                        echo "</a>";
                        echo "</div>";

                    }

                    echo "</td>";

                    echo "</tr>";
                }

                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="w3-right-align content-padding">
    <span>Tersedia</span> &nbsp;&nbsp; <i class="fas fa-circle w3-text-green fa-fw"></i><br>
    <span>Tidak tersedia</span> &nbsp;&nbsp; <i class="fas fa-circle w3-text-red fa-fw"></i><br>
    <span>Tersedia, masalah sedang ditindaklanjuti</span> &nbsp;&nbsp; <i class="fas fa-circle-half-stroke w3-text-orange fa-fw"></i>
</div>

<div class="w3-center w3-bar w3-padding-top-24 content-padding">
    <h3>Informasi System</h3>
</div>

<div class=" content-padding">
    <?php

    $sqlService = "SELECT * FROM service_main";
    $resultService = $connLog->query($sqlService);

    while($rowService = $resultService->fetch_assoc()) {

        echo "<div class='' style='margin-bottom: 64px;;'>";
        echo "<h5>{$rowService['service_name']} systems</h5>";
        echo "<table class='w3-table-all'>";

        echo "<thead>";
        echo "<tr class='w3-small'>";
        echo "<th style='width: 25%;'>SERVICE NAME</th>";
        echo "<th style='width: 25%;' class='w3-center'>STATUS</th>";
        echo "<th style='width: 50%;'>LAST UPDATED</th>";
        echo "</tr>";
        echo "</thead>";

        echo "<tbody>";
        $sqlSubservice = "SELECT * FROM service_sub WHERE parent_id = '{$rowService['id']}'";
        $resultSubservice = $connLog->query($sqlSubservice);

        while ($rowSubservice = $resultSubservice->fetch_assoc()) {
            echo "<tr id='{$rowSubservice['subservice_name']}'>";

            $statusIcon = parseStatusIcon($rowSubservice['status']);
            $actionIcon = parseActionIcon($rowSubservice['action']);

            echo "<td>{$rowSubservice['subservice_name']}</td>";
            echo "<td class='w3-center'>{$statusIcon}{$actionIcon}</td>";
            echo "<td>{$rowSubservice['last_updated']}</td>";

            echo "</tr>";
        }
        echo "</tbody>";

        echo "</table>";
        echo "</div>";

    }


    ?>
</div>


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