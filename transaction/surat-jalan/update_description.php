<?php
// Establish a database connection (assuming you're using MySQLi)
include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
$conn = getConnTransaction();

// Check the connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Get the ID and description from the AJAX request
$sjid = $_POST['sjid'];
$description = $_POST['description'];

// Prepare and execute the SQL update statement
$result = $conn->query("UPDATE surat_jalan SET description = '$description' WHERE surat_jalan_id = '$sjid'");

// Close the statement and database connection
$conn->close();

// Return a success response
echo 'Description updated successfully';
?>
