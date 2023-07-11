<?php
// Database configuration
$servername = "localhost";
$username = "nara";
$password = "12345678";
$dbname = "it_services";

// Create a database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
