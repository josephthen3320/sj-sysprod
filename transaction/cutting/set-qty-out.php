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

    $date = date('Y-m-d');

    setQtyOut($processName, $processId, $qty);
    setDateCutting($processId);

    $stid = createSuratJalan("CTO", $processId, 2, -1, $articleId, $qty, $uid);
    addSuratTerimaRecord($stid, "cutting", $processId);

    echo $closeWindowScript = "<script type='text/javascript'>window.close();</script>";

}

function setDateCutting($processId) {
    $conn = getConnTransaction();
    $date = date('Y-m-d');

    $sql = "UPDATE cutting SET date_cut = '$date' WHERE cutting_id = '$processId'";
    $conn->query($sql);
    $conn->close();
}

?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <title>Cutting: Qty Out</title>
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
    <h3>Cutting: Qty Out</h3>

    <span class="inline-header"><b>Cutting Id: </b></span><?= $processId ?><br>
    <span class="inline-header"><b>Article Id: </b></span><?= $articleId ?><br>
    <span class="inline-header"><b>Worksheet Id: </b></span><?= $worksheetId ?>

    <form action="" method="POST" class="w3-margin-top">
        <input hidden name="p" value="<?= $processName ?>">
        <input hidden name="i" value="<?= $processId ?>">

        <h6>Hasil Cutting: </h6>
        <label for="qtyOut">Qty: </label>
        <input class="w3-input w3-border w3-margin-bottom" type="number" min="0" name="qtyOut" id="qtyOut" autofocus>

        <button class="w3-button w3-block w3-blue-grey" type="submit">Submit</button>
    </form>
</div>

</body>