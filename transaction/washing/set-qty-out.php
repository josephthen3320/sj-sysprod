<?php
session_start();
$uid = $_SESSION['user_id'];

include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

$err = null;

if ($_SERVER['REQUEST_METHOD'] == "GET"); {
    $processName = $_GET['p'];
    $processId = $_GET['i'];
    $worksheetId = $_GET['w'];

    $qtyIn = $_GET['q'];

    $worksheet = fetchWorksheetData($worksheetId);
    $articleId = $worksheet['article_id'];
}

// $qtyTotalOut = getQtyOutTotal($processId)->fetch_assoc()['qty_total_out'];
// $qtyLeft = $qtyIn - $qtyTotalOut;

if (isset($_POST['i'])) {
    $qtyOut = $_POST['qtyOut'];

    // $qtyFail    = $_POST['qtyFail'] > 0 ? $_POST['qtyFail'] : 0;
    // $qtyDefect  = $_POST['qtyDefect'] > 0 ? $_POST['qtyDefect'] : 0;
    $qtyMissing = $_POST['qtyMissing'] > 0 ? $_POST['qtyMissing'] : 0;
    $description = $_POST['description'] ?? '';

    if (($qtyOut + $qtyMissing) > $qtyIn) {
        echo "Jumlah input lebih dari qty masuk!";
        exit();
    }
    /*
    $qtyLeft = $qtyIn - ($qtyTotalOut + $qtyOut + $qtyMissing);
    */

    updateWashingRecord($processId, $qtyOut, $qtyMissing);
    echo $closeWindowScript = "<script type='text/javascript'>window.close();</script>";


    /*
    if (($qtyFail + $qty) != $qtyIn) {
        $err = "Qty tidak seimbang";
        $err .= "<br><br>";
    } else {
        setQtyOut($processName, $processId, $qty);
        setQtyOther($processName, $processId, $qtyDefect, $qtyFail, $qtyMissing);

        echo $closeWindowScript = "<script type='text/javascript'>window.close();</script>";
    }
    */


}

?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <title>Washing: Qty Out</title>
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
    <h3>Washing: Qty Out</h3>

    <span class="w3-text-red" style="font-weight: bold;"><?= $err ?></span>

    <span class="inline-header"><b>Washing Id: </b></span><?= $processId ?><br>
    <span class="inline-header"><b>Article Id: </b></span><?= $articleId ?><br>
    <span class="inline-header"><b>Worksheet Id: </b></span><?= $worksheetId ?><br><Br>

    <span class="inline-header"><b>Qty Masuk: </b></span><?= $qtyIn ?> |

    <form action="" method="POST" class="w3-margin-top">
        <input hidden name="p" value="<?= $processName ?>">
        <input hidden name="i" value="<?= $processId ?>">

        <h6>Hasil Washing: </h6>
        <label for="qtyOut">Qty: </label>
        <input onwheel="event.preventDefault()" class="w3-input w3-border w3-margin-bottom" <?php // echo ($qtyLeft == 0) ? 'disabled' : ''; ?> type="number" min="0" name="qtyOut" id="qtyOut" autofocus required>

        <label for="qtyMissing">Qty Hilang: </label>
        <input onwheel="event.preventDefault()" class="w3-input w3-border w3-margin-bottom" <?php // echo ($qtyLeft == 0) ? 'disabled' : ''; ?> type="number" min="0" name="qtyMissing" id="qtyMissing">

        <?php
        $submitBtn = "<button class='w3-button w3-block w3-blue-grey' type='submit'>Submit</button>";

        // echo ($qtyLeft == 0) ? '' : $submitBtn;
        echo $submitBtn;

        ?>
    </form>
</div>

<?php

function updateWashingRecord($washingId, $qtyOut, $qtyMissing) {
    $conn = getConnTransaction();

    $sql = "UPDATE washing SET qty_out = '$qtyOut', qty_missing = '$qtyMissing' WHERE washing_id = '$washingId'";
    $conn->query($sql);
    $conn->close();
}

?>

</body>