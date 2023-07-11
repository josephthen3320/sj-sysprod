<?php
// Include the database connection file
require_once 'db.php';
$conn = getConnProduction();

// Query to fetch data from the "process_list" table and sort by the "id" field
$sql = "SELECT process_id, process_name FROM process_list ORDER BY id ASC";

// Execute the query
$result = mysqli_query($conn, $sql);

// Create an empty array to store the process data
$processes = array();

// Check if there are any rows returned
if (mysqli_num_rows($result) > 0) {
    // Iterate over each row and add it to the processes array
    while ($row = mysqli_fetch_assoc($result)) {
        $processes[] = $row;
    }
}

// Return the processes array as a PHP variable
return $processes;

// Assuming you have closed the database connection in the db_prod.php file
$conn->close();
?>
