<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet_position.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';

    $curProcessName = 'qc_embro';

    $uid = $_POST['uid'];

    $processName = strtolower($_POST['pname']);

    $wid = $_POST['w'];
    $tid = $_POST['tid'];
    $cmt = $_POST['cmt'];
    $qty = $_POST['qty'];
    $aid = $_POST['aid'];

    $transaction_id = $_POST['trid'];


    switch ($processName) {
        case 'print/sablon':
            echo "Pushing to Print/Sablon";

            pushToPrintSablon($wid, $cmt, $qty);
            updateWorksheetPosition($wid, 4); // Set to embro

            // Surat Jalan
            $sjid = createSuratJalan('QE', $transaction_id, 5, 4, $aid, $qty, $uid);
            addSuratJalanRecord($sjid, 'qc_embro', $transaction_id);

            echo "Pushing complete";
            break;

        case 'sewing':
            echo "Pushing to Sewing CMT";

            pushToSewing($wid, $cmt, $qty);
            updateWorksheetPosition($wid, 6); // Set to embro

            // Surat Jalan
            $sjid = createSuratJalan('QE', $transaction_id, 5, 6, $aid, $qty, $uid);
            addSuratJalanRecord($sjid, 'qc_embro', $transaction_id);

            echo "Pushing complete";
            break;
    }

    updateEndDate($curProcessName, $tid);
    toggleProcessCompleted($curProcessName, $wid);

    echo $closeWindowScript = "<script type='text/javascript'>window.close();</script>";

}