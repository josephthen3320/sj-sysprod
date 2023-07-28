<?php
session_start();
$uid = $_SESSION['user_id'];

include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

if ($_SERVER['REQUEST_METHOD'] == "GET"); {
    $processName = $_GET['p'];
    $processId = $_GET['i'];
    $worksheetId = $_GET['w'];

    $worksheet = fetchWorksheetData($worksheetId);
    $articleId = $worksheet['article_id'];
}

if (isset($_POST['i'])) {
    $processName = $_POST['p'];
    $processId = $_POST['i'];
    $qty = $_POST['qtyOut'];

    echo $qtyMissing = getTotalQtyMissing($worksheetId);
    echo $qtyDefect = $_POST['qtyDefect'] <= 0 ? 0 : $_POST['qtyDefect'];
    echo $qtyFail = $_POST['qtyFail'] <= 0 ? 0 : $_POST['qtyFail'];

    setQtyOut($processName, $processId, $qty);
    setQtyOther($processName, $processId, $qtyDefect, $qtyFail, $qtyMissing);

    /*
    $stid = createSuratTerima("EBO", $processId, 3, -1, $articleId, $qty, $uid);
    addSuratTerimaRecord($stid, "embro", $processId);
    */

    $closeWindowScript = "<script type='text/javascript'>window.close();</script>";

}
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <title>QC Final: Qty Out</title>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">

</head>

<style>
    .inline-header {
        display: inline-block;
        width: 100px;
    }
</style>

<body>

<div class="w3-container">
    <h3>QC Final: Qty Out</h3>

    <span class="inline-header"><b>Embro Id: </b></span><?= $processId ?><br>
    <span class="inline-header"><b>Article Id: </b></span><?= $articleId ?><br>
    <span class="inline-header"><b>Worksheet Id: </b></span><?= $worksheetId ?>

    <form action="" method="POST" class="w3-margin-top">
        <input hidden name="p" value="<?= $processName ?>">
        <input hidden name="i" value="<?= $processId ?>">


        <label for="qtyOut">Qty: </label>
        <input onwheel="event.preventDefault()" class="w3-input w3-border w3-margin-bottom" type="number" min="0" name="qtyOut" id="qtyOut" autofocus>

        <label for="qtyDefect">Qty Cacat: </label>
        <input onwheel="event.preventDefault()" class="w3-input w3-border w3-margin-bottom" type="number" min="0" name="qtyDefect" id="qtyDefect">

        <label for="qtyFail">Qty Gagal: </label>
        <input onwheel="event.preventDefault()" class="w3-input w3-border w3-margin-bottom" type="number" min="0" name="qtyFail" id="qtyFail">

        <button class="w3-button w3-block w3-blue-grey" type="submit">Submit</button>
    </form>
</div>

</body>