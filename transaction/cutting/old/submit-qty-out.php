<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db_combined.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';
    $conn = getConnTransaction();

    $id = $_POST['id'];
    $qty = $_POST['cutting_out'];

    $sql = "UPDATE cutting SET qty_out='$qty' WHERE id='$id'";
    $conn->query($sql);
    $conn->close();

    // Surat Jalan
    $sjid = createSuratCutting('CT', $transaction_id,  $aid, $qty, $uid);
    addSuratJalanRecord($sjid, 'cutting', $transaction_id);

}

echo $closeWindowScript = "<script type='text/javascript'>window.close();</script>";
