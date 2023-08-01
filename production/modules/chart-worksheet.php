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


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="chartWorksheetCount"></canvas>

<script>
    // Function to format the x-axis date labels without the year
    function formatDateWithoutYear(date) {
        if (!(date instanceof Date)) {
            date = new Date(date);
        }
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

    // Prepare data for the chart (parse the dates)
    var dates = <?php echo json_encode($dates); ?>;
    for (var i = 0; i < dates.length; i++) {
        dates[i] = formatDateWithoutYear(dates[i]);
    }

    // Chart configuration
    var ctx = document.getElementById('chartWorksheetCount').getContext('2d');
    var chartWorksheetCount = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Worksheet Count',
                    data: <?php echo json_encode(array_values($counts)); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        suggestedMin: 0,
                        suggestedMax: Math.max(...<?php echo json_encode(array_values($counts)); ?>) + 1,
                        grid: {
                            display: false
                        },
                        ticks: {
                            stepSize: 1,
                            callback: function(value, index, values) {
                            return Number.isInteger(value) ? value : '';
                            }
                        }
                    }
                }
            }
    });
</script>
