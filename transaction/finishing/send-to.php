<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet_position.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';

    $curProcessName = 'sewing';

    $uid = $_POST['uid'];

    $processName = strtolower($_POST['pname']);

    $wid = $_POST['w'];
    $tid = $_POST['tid'];
    $cmt = $_POST['cmt'];
    $qty = $_POST['qty'];
    $aid = $_POST['aid'];

    $transaction_id = $_POST['trid'];


    switch ($processName) {
        case 'washing':
            echo "Pushing to Washing";

            pushToWashing($wid, $cmt, $qty);
            updateWorksheetPosition($wid, 7);   // Set to washing

            // Surat Jalan
            $sjid = createSuratJalan('WI', $transaction_id, 8, 7, $aid, $qty, $uid);
            addSuratJalanRecord($sjid, 'finishing', $transaction_id);

            echo "Pushing complete";
            break;

        case 'qc final':
            echo "Pushing to QC Final";

            pushToQCFinal($wid, $qty);
            updateWorksheetPosition($wid, 9); // Set to qc final

            // Get all qty_missing from Sewing, Finishing, Washing
            $totalQtyMissing = getTotalQtyMissing($wid);
            setQtyMissing('qc_final', $transaction_id, $totalQtyMissing);

            // Surat Jalan
            $sjid = createSuratJalan('QF', $transaction_id, 8, 9, $aid, $qty, $uid);
            addSuratJalanRecord($sjid, 'finishing', $transaction_id);

            echo "Pushing complete";
            break;
    }

    updateEndDate($curProcessName, $tid);
    toggleProcessCompleted($curProcessName, $wid);

    echo $closeWindowScript = "<script type='text/javascript'>window.close();</script>";

}