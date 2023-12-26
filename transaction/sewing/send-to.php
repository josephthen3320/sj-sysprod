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
    $cmt = $_POST['cmt'] ?? '';
    $qty = $_POST['qty'];
    $aid = $_POST['aid'];

    $transaction_id = $_POST['trid'];


    switch ($processName) {
        case 'washing':
            echo "Pushing to Washing";

            pushToWashing($wid, $cmt, $qty);
            updateWorksheetPosition($wid, 7);   // Set to washing

            // Surat Jalan
            $sjid = createSuratJalan('SI', $transaction_id, 6, 7, $aid, $qty, $uid);
            addSuratJalanRecord($sjid, 'sewing', $transaction_id);

            echo "Pushing complete";
            break;

        case 'qc final':
            echo "Pushing to QC Final";

            pushToQCFinal($wid, $qty);
            updateWorksheetPosition($wid, 9); // Set to qc final

            // Surat Jalan
            $sjid = createSuratJalan('SI', $transaction_id, 6, 9, $aid, $qty, $uid);
            addSuratJalanRecord($sjid, 'sewing', $transaction_id);

            echo "Pushing complete";
            break;

        case 'finishing':
            echo "Pushing to Finishing";

            pushToFinishing($wid, $cmt, $qty);
            updateWorksheetPosition($wid, 8); // Set to finishing

            // Surat Jalan
            $sjid = createSuratJalan('SI', $transaction_id, 6, 8, $aid, $qty, $uid);
            addSuratJalanRecord($sjid, 'sewing', $transaction_id);

            echo "Pushing complete";
            break;

        case 'transit':
            echo "Pushing to Transit";

            pushToTransit($wid, $qty);
            updateWorksheetPosition($wid, '-2'); // Set to transit

            // Surat Jalan
            $sjid = createSuratJalan('TN', $transaction_id, 6, -2, $aid, $qty, $uid);
            addSuratJalanRecord($sjid, 'transit', $transaction_id);

            echo "Pushing complete";
            break;
    }

    updateEndDate($curProcessName, $tid);
    toggleProcessCompleted($curProcessName, $wid);

    echo $closeWindowScript = "<script type='text/javascript'>window.close();</script>";

}