<?php

// Function to fetch worksheet details by worksheet ID
function fetchWorksheetDetails($worksheetId) {
// Include the database connection file
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
    $conn = getConnProduction();

// Query to fetch data from the "worksheet_detail" table for a specific worksheet ID
$sql = "SELECT * FROM worksheet_detail WHERE worksheet_id = '$worksheetId'";

// Execute the query
$result = mysqli_query($conn, $sql);

// Create an empty array to store the worksheet details
$details = array();

// Check if there are any rows returned
if ($result && mysqli_num_rows($result) > 0) {
// Iterate over each row and add it to the details array
while ($row = mysqli_fetch_assoc($result)) {
$details[] = $row;
}
}

// Close the result set
mysqli_free_result($result);

// Close the database connection
mysqli_close($conn);

// Return the details array
return $details;
}
?>