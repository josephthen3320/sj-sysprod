<!DOCTYPE html>
<html>
<head>
    <title>UptimeRobot API Status</title>
</head>
<body>
<?php
// Replace 'YOUR_API_KEY' with your UptimeRobot API key
$apiKey = 'ur2246977-c99f4551019c1a0039173dd3';
$apiUrl = "https://api.uptimerobot.com/v2/getMonitors";

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('api_key' => $apiKey, 'format' => 'json', 'logs' => '1')));

// Execute cURL session and store the response
$response = curl_exec($ch);

// Close cURL session
curl_close($ch);

// Decode JSON response
$data = json_decode($response, true);

// Check if the response is valid
if ($data && isset($data['monitors'])) {
    foreach ($data['monitors'] as $monitor) {
        $status = ($monitor['status'] == 2) ? 'Up' : 'Down';
        $monitorName = $monitor['friendly_name'];

        echo "<p>Service: $monitorName - Status: $status ({$monitor['status']})</p>";
    }
} else {
    echo "<p>Error retrieving data from UptimeRobot API</p>";
}
?>
</body>
</html>