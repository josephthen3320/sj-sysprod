<?php
session_start();
$uid = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet_position.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';

    $curProcessName = 'embro';

    $wid = $_POST['w'];
    $tid = $_POST['tid'];
    $qty = $_POST['qty'];
    $aid = $_POST['aid'];

    $transaction_id = $_POST['trid'];


    pushToQCEmbro($wid, $qty);
    updateWorksheetPosition($wid, 5);   // Set to qc embro

    // Surat Jalan
    $sjid = createSuratTerima('EB', $transaction_id, 3, 5, $aid, $qty, $uid);
    addSuratTerimaRecord($sjid, 'embro', $transaction_id);

    updateEndDate($curProcessName, $tid);
    toggleProcessCompleted($curProcessName, $wid);

    echo $closeWindowScript = "<script type='text/javascript'>window.close();</script>";

}