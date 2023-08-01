<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnProduction();

// Get the current date and the day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
$today = strtotime('today');
$dayOfWeek = date('w', $today);

// Calculate the number of days to subtract to get the most recent Monday
$daysToSubtract = ($dayOfWeek + 6) % 7;

// Calculate the start date (most recent Monday)
$startDate = date('Y-m-d', strtotime("-{$daysToSubtract} days", $today));

// Calculate the end date (7 days after the start date)
$endDate = date('Y-m-d', strtotime("+6 days", strtotime($startDate)));

// Create an array containing all the dates within the one-week range
$allDates = [];
$currentDate = strtotime($startDate);
while ($currentDate <= strtotime($endDate)) {
    $allDates[] = date('Y-m-d', $currentDate);
    $currentDate = strtotime('+1 day', $currentDate);
}

// Fetch data from the database for the past week (from the most recent Monday)
$sql = "SELECT DATE(worksheet_date) AS date, COUNT(*) AS count FROM worksheet WHERE worksheet_date >= '$startDate' AND worksheet_date <= '$endDate' GROUP BY DATE(worksheet_date) ORDER BY DATE(worksheet_date)";
$result = $conn->query($sql);

// Prepare data for the chart
$dates = $allDates;
$counts = array_fill_keys($allDates, 0);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $date = $row["date"];
        $count = $row["count"];
        $counts[$date] = $count;
    }
}
?>

<?php
$totalWeekWorksheets = array_sum($counts);
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

</head>
<body>

<div class="w3-row" style="height: 60px;">
    <div class="w3-col l2 m3 s4 w3-xlarge w3-center">
        <h6 class="w3-small"><b>Total worksheets</b></h6>
        <h3><?= 000 ?></h3>
    </div>
    <div class="w3-col l2 m3 s4 w3-xlarge w3-center">
        <h6 class="w3-small"><b>This week's</b></h6>
        <h3><?= $totalWeekWorksheets ?></h3>
    </div>
    <div class="w3-col l2 m3 s4 w3-xlarge w3-center">
        <h6 class="w3-small"><b>Total worksheets</b></h6>
    </div>
    <div class="w3-col l2 m3 s4 w3-xlarge w3-center">
        <h6 class="w3-small"><b>Total worksheets</b></h6>
    </div>
</div>

</body>
</html>