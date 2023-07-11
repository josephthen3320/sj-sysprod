<?php
// Include the database connection file
require_once 'db-its.php';

// Get the ticket ID and status from the form submission
$ticketId = $_POST['ticket_id'];
$status = $_POST['status'];

// Update the ticket status in the database
$sql = "UPDATE tickets SET status = '$status' WHERE id = $ticketId";

if (mysqli_query($conn, $sql)) {
    // Status updated successfully
    // Redirect the user back to the tickets page
    header('Location: ../tickets.php');
    exit;
} else {
    // Handle the error
    echo "Error updating status: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
