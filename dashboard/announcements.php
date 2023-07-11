<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement List</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>

<body>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$connLog = getConnLog();

$sql = "SELECT * FROM global_announcements ORDER BY timestamp DESC LIMIT 5";
$result = $connLog->query($sql);

if ($result->num_rows <= 0) {
    echo "<div class='w3-bar-item w3-leftbar w3-border-green w3-hover-light-grey' style='padding: 16px 40px 16px 20px;'>";
    echo "<div class='w3-container w3-cell w3-cell-middle'>";
    echo "<i class='fa-solid fa-smile-beam fa-2xl w3-text-green'></i>";
    echo "</div>";
    echo "<div class='w3-container w3-cell w3-cell-middle w3-display-container' style='width:100%;'>";
    echo "<span class='w3-center' style='font-weight: bold;'>No announcement yet!</span><br>";
    echo "<span></span>";
    echo "</div>";
    echo "</div>";
}

while ($a = $result->fetch_assoc()) {

    switch ($a['type']) {
        default:
        case 0:                     // Normal announcement
            $icon = "fa-exclamation-circle";
            $colour = "blue";
            break;
        case 1:                     // Critical
            $icon = "fa-exclamation-triangle";
            $colour = "yellow";
            break;
        case 2:                     // Service Down
            $icon = "fa-down";
            $colour = "red";
            break;
        case 3:                     // Bug
            $icon = "fa-bug";
            $colour = "orange";
            break;
        case 4:                     // Resolved
            $icon = "fa-circle-check";
            $colour = "green";
            break;
        case 5:                     // Service Up
            $icon = "fa-up";
            $colour = "green";
            break;

    }

    echo "<div class='w3-border-top w3-border-bottom w3-border-light-grey'>";
    echo "<div class='w3-bar-item w3-leftbar w3-border-{$colour} w3-hover-light-grey' style='padding: 16px 40px 16px 20px;'>";
    echo "<div class='w3-container w3-cell w3-cell-middle'>";
    echo "<i class='fa-solid {$icon} fa-2xl w3-text-{$colour}'></i>";
    echo "</div>";
    echo "<div class='w3-container w3-cell w3-cell-middle w3-display-container' style='width:100%;'>";
    echo "<span class='w3-small w3-hide-medium w3-hide-large'><b>{$a['timestamp']}<br></b></span>";
    echo "<span class='w3-small w3-display-topright w3-hide-small'><b>{$a['timestamp']}</b></span>";
    echo "<span style='font-weight: bold;'>{$a['subject']}</span> <br>";
    echo "<span>{$a['details']}</span><br>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}
?>

</body>
</html>

<script src="/assets/js/utils.js"></script>
