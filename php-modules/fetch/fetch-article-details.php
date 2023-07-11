<?php
// Include the database connection file
require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
$conn = getConnProduction();

// Get the articleId from the request parameters
$articleId = $_GET['articleId'];

// Query to fetch the article details based on the articleId
$sql = "SELECT * FROM article WHERE article_id = '$articleId'";

// Execute the query
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result) {
    // Fetch the article details as an associative array
    $articleDetails = mysqli_fetch_assoc($result);

    // Return the article details as JSON response
    header('Content-Type: application/json');
    echo json_encode($articleDetails);
} else {
    // Return an error message if the query failed
    $error = mysqli_error($conn);
    echo json_encode(['error' => $error]);
}

// Close the database connection
$conn->close();
?>
