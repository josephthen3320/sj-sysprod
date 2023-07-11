<?php
// Include the database connection file
require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
$conn = getConnProduction();

// Get the brandId from the request parameters
$brandId = $_GET['brandId'];

// Query to fetch the brand_name based on the brandId
$sql = "SELECT brand_name FROM brand WHERE brand_id = '$brandId'";

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    // Fetch the brand_name
    $row = $result->fetch_assoc();
    $brandName = $row['brand_name'];

    // Return the brand_name as a JSON response
    echo json_encode(['brand_name' => $brandName]);
} else {
    // Return an error message if the query fails
    echo json_encode(['error' => 'Failed to fetch brand_name']);
}

// Close the database connection
$conn->close();
?>
