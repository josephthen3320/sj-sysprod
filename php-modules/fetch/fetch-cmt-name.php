<?php
// Include the database connection file
require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
$conn = getConnProduction();

// Get the cmtId from the request parameters
$cmtId = $_GET['cmtId'];

// Query to fetch the cmt_name based on the cmtId
$sql = "SELECT cmt_name FROM cmt WHERE cmt_id = '$cmtId'";

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    // Fetch the cmt_name
    $row = $result->fetch_assoc();
    $cmtName = $row['cmt_name'];

    // Return the cmt_name as a JSON response
    echo json_encode(['cmt_name' => $cmtName]);
} else {
    // Return an error message if the query fails
    echo json_encode(['error' => 'Failed to fetch cmt_name']);
}

// Close the database connection
$conn->close();
?>
