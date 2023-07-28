<?php
session_start();
$uid = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet_position.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';

    $curProcessName = 'qc_final';

    $wid = $_POST['w'];
    $tid = $_POST['tid'];
    //$cmt = $_POST['cmt'];
    $qty = $_POST['qty'];
    $aid = $_POST['aid'];

    $transaction_id = $_POST['trid'];

    // todo: Logic for pushing to warehouse / service

    // Send to warehouse
    pushToWarehouse($wid, $qty);
    updateWorksheetPosition($wid, 11);   // Set to finishing
    updateEndDate($curProcessName, $tid);
    toggleProcessCompleted($curProcessName, $wid);

    // Surat Jalan
    $sjid = createSuratJalan('QF', $transaction_id, 9, 11, $aid, $qty, $uid);
    addSuratJalanRecord($sjid, 'qc_final', $transaction_id);

    // todo: Logic for accepted; rejected; other [qty out]



/*
    pushToFinishing($wid, $cmt, $qty);
    updateWorksheetPosition($wid, 10);   // Set to finishing

    // Surat Jalan
    $sjid = createSuratTerima('QF', $transaction_id, 10, 10, $aid, $qty, $uid);
    addSuratTerimaRecord($sjid, 'qc_final', $transaction_id);

    updateEndDate($curProcessName, $tid);
    toggleProcessCompleted($curProcessName, $wid);

    echo $closeWindowScript = "<script type='text/javascript'>window.close();</script>";
*/

}