<?php
session_start();
$uid = $userId = $_SESSION['user_id'];

$root = $_SERVER['DOCUMENT_ROOT'];

/*
if (!isset($_POST['worksheet_id'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
*/

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    echo "<h1> Success! </h1>";
}

include_once $root . "/php-modules/db.php";
$conn = getConnProduction();

$article_id     = $_POST['article_id'];

if (!$article_id) {
    $_SESSION['msg'] = "Error: Article cannot be empty.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$worksheet_id   = $_POST['worksheet_id'];
$delivery_date  = $_POST['delivery_date'];
$delivery_day   = $_POST['delivery_day_date'];
$po_date        = $_POST['po_date'];

$deliveryDate = date('Y-m-d', strtotime($delivery_date . '-' . $delivery_day));


$po_date = !$po_date ? null : $po_date;

$sql = "INSERT INTO worksheet (worksheet_id, delivery_date, po_date, created_by) VALUES ('$worksheet_id', '$deliveryDate', '$po_date', '$userId')";
$conn->query($sql);

$qty            = $_POST['qty'];
$customer_id    = $_POST['customer_id'];
$cloth_width    = $_POST['cloth_width'];
$isFob          = !$_POST['is_fob'] ? null : $_POST['is_fob'];
$description    = $_POST['description'];


$sql = "INSERT INTO worksheet_detail (worksheet_id, article_id, qty, customer_id, cloth_width, is_fob, description)
             VALUES ('$worksheet_id', '$article_id', '$qty', '$customer_id', '$cloth_width', '$isFob', '$description')";
$conn->query($sql);


include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet_position.php";
updateWorksheetPosition($worksheet_id, 0);
$conn->close();

include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/agents/logging.php";
logGeneric($uid, 421, "WORKSHEET CREATED; worksheet_id={$worksheet_id};");


$_SESSION['success_msg'] = "Worksheet created!";
header("LOCATION: " . $_SERVER['HTTP_REFERER']);
exit();