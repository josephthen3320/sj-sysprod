<?php
session_start();
$uid = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet_position.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';

    $curProcessName = 'washing';

    $wid = $_POST['w'];
    $tid = $_POST['tid'];
    $cmt = $_POST['cmt'];
    $qty = $_POST['qty'];
    $aid = $_POST['aid'];

    $transaction_id = $_POST['trid'];


    pushToFinishing($wid, $cmt, $qty);
    updateWorksheetPosition($wid, 8);   // Set to finishing

    // Surat Jalan
    $sjid = createSuratTerima('WI', $transaction_id, 7, 8, $aid, $qty, $uid);
    addSuratTerimaRecord($sjid, 'washing', $transaction_id);

    updateEndDate($curProcessName, $tid);
    toggleProcessCompleted($curProcessName, $wid);

    echo $closeWindowScript = "<script type='text/javascript'>window.close();</script>";

}