<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet_position.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';

    $curProcessName = 'cutting';

    $uid = $_POST['uid'];

    $processName = strtolower($_POST['pname']);

    $wid = $_POST['w'];
    $tid = $_POST['tid'];
    $cmt = $_POST['cmt'];
    $qty = $_POST['qty'];
    $aid = $_POST['aid'];

    $transaction_id = $_POST['trid'];


    switch ($processName) {
        case 'embro':
            echo "Pushing to Embro";

            pushToEmbro($wid, $cmt);
            updateWorksheetPosition($wid, 3); // Set to embro

            // Surat Jalan
            $sjid = createSuratJalan('CT', $transaction_id, 2, 3, $aid, 1, $uid);
            addSuratJalanRecord($sjid, 'cutting', $transaction_id);

            echo "Pushing complete";
            break;
        case 'print/sablon':
            echo "Pushing to Print/Sablon";

            pushToPrintSablon($wid, $cmt);
            updateWorksheetPosition($wid, 4); // Set to embro

            // Surat Jalan
            $sjid = createSuratJalan('CT', $transaction_id, 2, 4, $aid, 1, $uid);
            addSuratJalanRecord($sjid, 'cutting', $transaction_id);

            echo "Pushing complete";
            break;
        case 'sewing':
            echo "Pushing to Sewing CMT";

            pushToSewing($wid, $cmt);
            updateWorksheetPosition($wid, 6); // Set to embro

            // Surat Jalan
            $sjid = createSuratJalan('CT', $transaction_id, 2, 6, $aid, 1, $uid);
            addSuratJalanRecord($sjid, 'cutting', $transaction_id);

            echo "Pushing complete";
            break;
    }

    updateEndDate($curProcessName, $tid);
    toggleProcessCompleted($curProcessName, $wid);


}