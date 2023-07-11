<?php
// create_ticket.php

// Include the database connection file
require_once 'db-its.php';

// Get the form data
$subject        = $_POST['subject'];
$requester      = $_POST['requester'];
$department     = $_POST['department'];
$status         = $_POST['status'];
$priority       = $_POST['priority'];
$ticket_type    = $_POST['type'];
$module         = $_POST['module'];
$assignee       = $_POST['assignee'];
$description    = $_POST['description'];
$labels         = $_POST['labels'];

// Insert the ticket into the database
/*$sql = "INSERT INTO tickets (title, description, status, created_at, submitted_by, department)
        VALUES ('$title', '$description', 'Open', NOW(), '$submittedBy', '$department')";
*/
    $sql = "INSERT INTO ticket (subject, requester_name, department, status, priority, ticket_type, issue_module, description, assignee, labels)
            VALUES ('$subject','$requester','$department','$status','$priority','$ticket_type','$module','$description','$assignee','$labels')";

// Execute the query
if (mysqli_query($conn, $sql)) {
    // Redirect the user back to tickets.html
    header('Location: ../');
    exit;
} else {
    // Handle the error
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
