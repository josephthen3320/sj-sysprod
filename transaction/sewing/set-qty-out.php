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

$qtyTotalOut = getQtyOutTotal($processId)->fetch_assoc()['qty_total_out'];
$qtyLeft = $qtyIn - $qtyTotalOut;

if (isset($_POST['i'])) {
    $qtyOut = $_POST['qtyOut'];

    // $qtyFail    = $_POST['qtyFail'] > 0 ? $_POST['qtyFail'] : 0;
    // $qtyDefect  = $_POST['qtyDefect'] > 0 ? $_POST['qtyDefect'] : 0;
    $qtyMissing = $_POST['qtyMissing'] > 0 ? $_POST['qtyMissing'] : 0;
    $description = $_POST['description'] ?? '';

    if (($qtyOut + $qtyMissing) > $qtyLeft) {
        echo "Jumlah input lebih dari sisa di CMT!";
        exit();
    }

    $qtyLeft = $qtyIn - ($qtyTotalOut + $qtyOut + $qtyMissing);

    insertSewingOutRecord($processId, $qtyOut, $qtyMissing, $qtyLeft, $description, $uid);
    updateSewingMasterRecord($processId);

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


$qtyTotalOut = getQtyOutTotal($processId)->fetch_assoc()['qty_total_out'];
$qtyLeft = $qtyIn - $qtyTotalOut;

?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <title>Sewing: Qty Out</title>
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
    <h3>Sewing: Qty Out</h3>

    <span class="w3-text-red" style="font-weight: bold;"><?= $err ?></span>

    <span class="inline-header"><b>Sewing Id: </b></span><?= $processId ?><br>
    <span class="inline-header"><b>Article Id: </b></span><?= $articleId ?><br>
    <span class="inline-header"><b>Worksheet Id: </b></span><?= $worksheetId ?><br><Br>

    <span class="inline-header"><b>Qty Masuk: </b></span><?= $qtyIn ?> |
    <span class="inline-header"><b>Qty Tersisa: </b></span><?= $qtyIn - $qtyTotalOut ?>

    <form action="" method="POST" class="w3-margin-top">
        <input hidden name="p" value="<?= $processName ?>">
        <input hidden name="i" value="<?= $processId ?>">

        <h6>Hasil Sewing: </h6>
        <label for="qtyOut">Qty: </label>
        <input onwheel="event.preventDefault()" class="w3-input w3-border w3-margin-bottom" <?= ($qtyLeft == 0) ? 'disabled' : ''; ?> type="number" min="0" name="qtyOut" id="qtyOut" autofocus required>

        <label for="description">Keterangan: </label>
        <input class="w3-input w3-border w3-margin-bottom" <?= ($qtyLeft == 0) ? 'disabled' : ''; ?> type="text" name="description" id="description">

        <label for="qtyMissing">Qty Hilang: </label>
        <input onwheel="event.preventDefault()" class="w3-input w3-border w3-margin-bottom" <?= ($qtyLeft == 0) ? 'disabled' : ''; ?> type="number" min="0" name="qtyMissing" id="qtyMissing">

        <!--
        <h6>Cacat/Gagal/Hilang</h6>
        <label for="qtyDefect">Cacat: </label>
        <input onwheel="event.preventDefault()" class="w3-input w3-border w3-margin-bottom" type="number" min="0" name="qtyDefect" id="qtyDefect">
        <label for="qtyFail">Gagal: </label>
        <input onwheel="event.preventDefault()" class="w3-input w3-border w3-margin-bottom" type="number" min="0" name="qtyFail" id="qtyFail">
        <label for="qtyMissing">Hilang: </label>
        <input onwheel="event.preventDefault()" class="w3-input w3-border w3-margin-bottom" type="number" min="0" name="qtyMissing" id="qtyMissing">
        -->

        <?php 
        $submitBtn = "<button class='w3-button w3-block w3-blue-grey' type='submit'>Submit</button>";

        echo ($qtyLeft == 0) ? '' : $submitBtn;
        
        ?>
    </form>
</div>


<!--script>
    // Get the input elements
    var qtyIn = <?= $qtyIn ?>;
    var qtyOutInput = document.getElementById('qtyOut');
    var qtyDefectInput = document.getElementById('qtyDefect');
    var qtyFailInput = document.getElementById('qtyFail');
    var qtyMissingInput = document.getElementById('qtyMissing');
    var qtyLeftSpan = document.getElementById('qtyLeft');

    // Add event listeners to the input fields
    qtyOutInput.addEventListener('input', calculateQtyLeft);
    qtyDefectInput.addEventListener('input', calculateQtyLeft);
    qtyFailInput.addEventListener('input', calculateQtyLeft);
    qtyMissingInput.addEventListener('input', calculateQtyLeft);

    // Calculate and update qtyLeft based on the input values
    function calculateQtyLeft() {
        var qtyOut = parseInt(qtyOutInput.value) || 0;
        var qtyDefect = parseInt(qtyDefectInput.value) || 0;
        var qtyFail = parseInt(qtyFailInput.value) || 0;
        var qtyMissing = parseInt(qtyMissingInput.value) || 0;

        var qtyLeft = qtyIn - (qtyOut + qtyDefect + qtyFail + qtyMissing);
        qtyLeftSpan.textContent = qtyLeft;

        if (qtyLeft === 0) {
            qtyLeftSpan.classList.remove('w3-text-red');
            qtyLeftSpan.classList.add('w3-text-green');
        } else {
            qtyLeftSpan.classList.remove('w3-text-green');
            qtyLeftSpan.classList.add('w3-text-red');
        }
    }
</script-->

</body>