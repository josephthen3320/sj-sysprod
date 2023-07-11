<?php
// Include the database connection file
require_once 'db-its.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the ticket ID and progress notes from the form
    $ticketID = $_POST['ticket_id'];
    $progressNotes = $_POST['progress_notes'];

    // Update the progress notes in the database
    $sql = "UPDATE tickets SET progress_notes = '$progressNotes' WHERE id = $ticketID";
    if (mysqli_query($conn, $sql)) {
        echo "Progress notes updated successfully.";
		    header('Location: tickets.php');
			exit;
    } else {
        echo "Error updating progress notes: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>
