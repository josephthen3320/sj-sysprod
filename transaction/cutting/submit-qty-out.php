<?php
session_start();
$uid = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

    $conn = getConnTransaction();

    $id = $_POST['id'];
    $qty = $_POST['cutting_out'];

    $tid = $_POST['tid'];
    $wid = $_POST['wid'];

    $worksheet = fetchWorksheetData($wid);
    $aid = $worksheet['article_id'];

    $sql = "UPDATE cutting SET qty_out='$qty' WHERE id='$id'";
    $conn->query($sql);
    $conn->close();

    // Surat Jalan
    $sjid = createSuratCutting('CTO', $tid,  $aid, $qty, $uid);
    addSJCuttingRecord($sjid, 'cutting', $tid);

}

//echo $closeWindowScript = "<script type='text/javascript'>window.close();</script>";
