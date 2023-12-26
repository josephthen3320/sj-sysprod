<?php

// cPanel credentials
$cpanelUsername = 'subm6595';
$cpanelPassword = 'vp1vC7UH1Ew855';
$cpanelDomain = 'dhaulagiri.iixcp.rumahweb.net';

$emailAccount = "hr@suburjayabdg.com"; // Replace with the email account you want to fetch messages for


$apiUrl = "https://$cpanelDomain:2083/execute/Email/list_mail";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Basic ' . base64_encode($cpanelUsername . ':' . $cpanelPassword),
    'Content-Type: application/json'
));
$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    $data = json_decode($response, true);
    if (isset($data['data'])) {
        $emailMessages = $data['data'];
        if (count($emailMessages) > 0) {
            echo "Email Messages for $emailAccount:<br>";
            foreach ($emailMessages as $message) {
                if ($message['email'] === $emailAccount) {
                    echo "Subject: " . $message['subject'] . "<br>";
                    echo "Date: " . $message['date'] . "<br>";
                    // Add more fields as needed
                    echo "<hr>";
                }
            }
        } else {
            echo "No email messages found.";
        }
    } else {
        echo "Error: Failed to retrieve email messages.";
    }
} else {
    echo "Error: Failed to connect to cPanel API.";
}

/*
// API endpoint
$apiUrl = "https://$cpanelDomain:2083/execute/Email/list_pops";

// cURL request to fetch email addresses
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Basic ' . base64_encode($cpanelUsername . ':' . $cpanelPassword),
    'Content-Type: application/json'
));
$response = curl_exec($ch);
curl_close($ch);

// Parse and display the email addresses
if ($response) {
    $data = json_decode($response, true);
    if (isset($data['data'])) {
        $emailAddresses = $data['data'];
        if (count($emailAddresses) > 0) {
            echo "Email Addresses:<br>";
            foreach ($emailAddresses as $email) {
                echo $email['email'] . "<br>";
            }
        } else {
            echo "No email addresses found.";
        }
    } else {
        echo "Error: Failed to retrieve email addresses.";
    }
} else {
    echo "Error: Failed to connect to cPanel API.";
}


/*
include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
$conn = getConnTransaction();


$sql = "SELECT t.*, p.position_id, p.pola_marker FROM pola_marker AS t INNER JOIN position AS p ON t.worksheet_id = p.worksheet_id ORDER BY p.pola_marker ASC, t.pola_marker_id DESC";
$result = mysqli_query($conn, $sql);

// Print out the structure of the query result
if ($result) {
    echo "Query Result Structure:<br>";
    $fields = mysqli_fetch_fields($result);
    foreach ($fields as $field) {
        echo "Name: " . $field->name . "<br>";
        echo "Type: " . $field->type . "<br>";
        echo "Length: " . $field->length . "<br>";
        echo "Flags: " . $field->flags . "<br>";
        echo "<br>";
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the database conn
mysqli_close($conn);
*/