<?php
session_start();
$uid = $_SESSION['user_id'];

include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet_position.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';

    $wid = $_GET['w'];
    $tid = $_GET['i'];
    $qty = $_GET['q'];

    echo "WID: $wid | TID: $tid";

    // Submit new Pola Marker job
    pushToEmbro($wid, $qty);

    // Update end date
    updateEndDate('cutting', $tid);
    toggleProcessCompleted('cutting', $wid);

    // Set Worksheet position to PM
    updateWorksheetPosition($wid, 3);

}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
