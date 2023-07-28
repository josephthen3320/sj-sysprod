<?php
// Make sure the session is started
session_start();

// Check if the POST data is received and not empty
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Validate and sanitize the new role value (you may have specific validation rules)
    $newRole = htmlspecialchars($_POST['newRole'], ENT_QUOTES, 'UTF-8');

    // Update the session variable with the new role value
    $_SESSION['user_role'] = $newRole;

    // Return a response to the client (optional, can be used for debugging or handling success/error messages)
    echo "User role updated successfully! [{$_SESSION['user_role']}]";
} else {
    // Return an error response to the client if the POST data is missing or empty
    echo "Error: Missing or empty 'newRole' data!";
}
?>