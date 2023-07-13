<?php
session_start();
$uid = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet_position.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';

    $curProcessName = 'pola_marker';

    $wid = $_POST['w'];
    $tid = $_POST['tid'];
    $cmt = $_POST['cmt'];
    $aid = $_POST['aid'];

    $transaction_id = $_POST['trid'];


    pushToCutting($wid, $cmt);
    updateWorksheetPosition($wid, 2);   // Set to cutting

    // Surat Jalan
    $sjid = createSuratJalan('PM', $transaction_id, 1, 2, $aid, 1, $uid);
    addSuratJalanRecord($sjid, 'pola_marker', $transaction_id);

    updateEndDate($curProcessName, $tid);
    toggleProcessCompleted($curProcessName, $wid);

    echo $closeWindowScript = "<script type='text/javascript'>window.close();</script>";

}