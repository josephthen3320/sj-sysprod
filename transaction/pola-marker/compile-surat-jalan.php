<?php
$selectedRows = json_decode(file_get_contents('php://input'), true);

if (!empty($selectedRows)) {
    foreach ($selectedRows as $row) {
        // Process each selected row
        $worksheetId = $row['worksheetId'];
        // ... perform your actions with the worksheet ID
    }


    // Return a response if necessary
    $response = "Selected rows processed successfully";
    echo $response;
} else {
    echo "No selected rows received";
}
?>