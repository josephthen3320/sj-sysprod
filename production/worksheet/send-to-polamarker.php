<?php
session_start();
$uid = $_SESSION['user_id'];

include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet_position.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';

    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/agents/logging.php';

    $wid = $_GET['w'];

    // Submit new Pola Marker job
    pushToPolaMarker($wid);
    //submitPolaMarker($pmid, $wid, $uid);

    // Set Worksheet position to PM
    updateWorksheetPosition($wid, 1);

    $details = "WORKSHEET PUSHED;";
    $details .= "worksheet_id={$wid};";
    $details .= "next_process=PM(1);";

    logGeneric($uid, 426, $details);

}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
