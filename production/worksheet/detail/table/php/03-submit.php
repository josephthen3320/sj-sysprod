<?php
// Retrieve the submitted data
$rowIndex = $_POST['rowIndex'];
$columnIndex = $_POST['columnIndex'];
$value = $_POST['value'];
$worksheet_id = $_POST['worksheetId'];

// Perform any necessary validation or processing

// Connect to the database (replace with your own database connection code)

// Connect to the database (replace with your own database connection code)
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
$conn = getConnWorksheet();

// Check if the cell already has a value in the database
$sql = "SELECT * FROM test_susut WHERE worksheet_id = '$worksheet_id' AND x_loc = $columnIndex AND y_loc = $rowIndex";
$result = $conn->query($sql);

if ($result !== false && $result->num_rows > 0) {
    // Cell already has a value, update the existing value in the table
    $updateSql = "UPDATE test_susut SET value = '$value' WHERE worksheet_id = '$worksheet_id' AND x_loc = $columnIndex AND y_loc = $rowIndex";
    if ($conn->query($updateSql) === true) {
        // Update successful
        $response = array('status' => 'success', 'message' => 'Data updated successfully');
    } else {
        // Update failed
        $response = array('status' => 'error', 'message' => 'Error updating data: ' . $conn->error);
    }
} else {
    // Cell is empty, insert the new value into the table
    $insertSql = "INSERT INTO test_susut (worksheet_id, x_loc, y_loc, value) VALUES ('$worksheet_id', $columnIndex, $rowIndex, '$value')";
    if ($conn->query($insertSql) === true) {
        // Insert successful
        $response = array('status' => 'success', 'message' => 'Data inserted successfully');
    } else {
        // Insert failed
        $response = array('status' => 'error', 'message' => 'Error inserting data: ' . $conn->error);
    }
}

// Close the database connection
$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
