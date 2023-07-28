<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/agents/logging.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newDate'])) {
    // Assuming you have a function to sanitize and validate the date input
    $newDate = $_POST['newDate'];
    $oldDate = $_POST['oldDate'];
    $sjid = $_POST['sjid'];

    $conn = getConnTransaction();

    $sql = "UPDATE surat_jalan SET send_date='$newDate' WHERE surat_jalan_id = '$sjid'";
    $conn->query($sql);
    $conn->close();

    logGeneric($_SESSION['user_id'], 492, "SEND DATE MODIFIED;id={$sjid};original_date={$oldDate};new_date={$newDate}");

    // Return a response to the client (optional, can be used for debugging or handling success/error messages)
    echo "Date updated successfully!";
}
?>
