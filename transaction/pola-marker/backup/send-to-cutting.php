<?php
session_start();
$uid = $_SESSION['user_id'];

include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet_position.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';

    $wid = $_POST['w'];
    $tid = $_POST['i'];
    $cmt = $_POST['cmt'];
    $aid = $_POST['a'];

    echo "WID: $wid | TID: $tid | CMT: $cmt";

    // Submit new Pola Marker job
    pushToCutting($wid, $cmt);

    // Update end date
    updateEndDate('pola_marker', $tid);
    toggleProcessCompleted('pola_marker', $wid);
    //submitPolaMarker($pmid, $wid, $uid);

    // Set Worksheet position to PM
    updateWorksheetPosition($wid, 2);

    // Surat Jalan
    $transaction_id = getPolaMarkerId($tid);
    $sjid = createSuratJalan('PM', $transaction_id, 1, 2, $aid, 1, $uid);
    addSuratJalanRecord($sjid, 'pola_marker', $transaction_id);

}

echo $closeWindowScript = "<script type='text/javascript'>window.close();</script>";
exit();


function getPolaMarkerId($tid) {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
    $conn = getConnTransaction();

    $sql = "SELECT pola_marker_id FROM pola_marker WHERE id = '$tid'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc()['pola_marker_id'];
    $conn->close();

    return $row;
}