<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$log_conn = getConnLog();

// Calculate the date 7 days ago
$startDate = date('Y-m-d', strtotime('-7 days'));

// Fetch data from the database for the past 7 days
$sql = "SELECT DATE(timestamp) AS date, COUNT(*) AS count FROM user_activity_log WHERE timestamp >= '$startDate' GROUP BY DATE(timestamp) ORDER BY DATE(timestamp)";
$result = $log_conn->query($sql);

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


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="myChart"></canvas>

<script>
    // Chart configuration
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'User Activity Count',
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
