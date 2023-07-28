<?php
// update_status.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['serviceSubId']) && isset($_POST['newStatus'])) {
    $serviceSubId = $_POST['serviceSubId'];
    $newStatus = $_POST['newStatus'];

    $current_timezone = date_default_timezone_set('Asia/Jakarta'); // Replace 'America/New_York' with your preferred timezone

    $date = date("Y-m-d H:i:s");

    // Validate the new status value (optional, you can perform additional validation if needed)

    // Perform the database update
    include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnLog();
    $updateStatusSQL = "UPDATE service_sub SET status = '$newStatus', last_updated = '$date' WHERE id = '$serviceSubId'";
    $conn->query($updateStatusSQL);
    $conn->close();

    // Return a response to the client (optional, can be used for debugging or handling success/error messages)
    echo "Status updated successfully!";
}
?>
