<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnWorksheet();

// Retrieve the data from the 'material_accessories' table for the given worksheet_id
$sql = "SELECT * FROM material_accessories WHERE worksheet_id = '$worksheet_id'";
$result = $conn->query($sql);

if ($result) {
    // Fetch the data and build an associative array with row and column indexes as keys
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $rowIndex = $row['y_loc'];
        $columnIndex = $row['x_loc'];
        $value = $row['value'];
        $data[$rowIndex][$columnIndex] = $value;
    }

    // Generate the JavaScript code to update the HTML table cells
    $jsCode = '';
    foreach ($data as $rowIndex => $row) {
        foreach ($row as $columnIndex => $value) {
            $jsCode .= "document.querySelector('#tableMaterialAccessories tbody tr:nth-child(" . ($rowIndex + 1) . ") td:nth-child(" . ($columnIndex + 1) . ")').textContent = '" . addslashes($value) . "';\n";
        }
    }

    // Output the JavaScript code
    echo "<script>" . $jsCode . "</script>";
} else {
    // Query failed
    echo 'Error: ' . $conn->error;
}

?>