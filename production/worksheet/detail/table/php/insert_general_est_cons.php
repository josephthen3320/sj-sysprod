<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
$conn = getConnProduction();

$worksheetId = $_POST['worksheet_id'];
$description = $_POST['description'];

// todo: echo this below on console for debugging
$sql = "UPDATE worksheet_detail SET general_est_cons = '$description' WHERE worksheet_id = '$worksheetId'";

$conn->query($sql);
$conn->close();

// Create an array to hold the values
$response = array(
    'sql' => $sql,
    'worksheetId' => $worksheetId,
    'description' => $description
);

// Send the response back as JSON
echo json_encode($response);
?>