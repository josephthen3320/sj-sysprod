<?php
session_start();
$uid = $_SESSION['user_id'];

$closeWindowScript = "<script type='text/javascript'>window.close();</script>";

if (!isset($_GET)) { echo $closeWindowScript; }

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

    $w  = $_GET['w'];
    $i  = $_GET['i'];
    $pi = $_GET['pi'];
    $qty = $_GET['q'];

    $worksheet = fetchWorksheetData($w);
    $a = $worksheet['article_id'];

}

?>


<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send to QC Embro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>

<body>

    <div class="w3-container">
        <h1>Send to QC Embro</h1>
        <span style="display: inline-block; width: 120px; font-weight: bold">Worksheet ID: </span><?= $w ?>
        <br>
        <span style="display: inline-block; width: 120px; font-weight: bold">Article ID: </span><?= $a ?>
        <br>
        <span style="display: inline-block; width: 120px; font-weight: bold">Embro ID: </span><?= $pi ?>
        <br><br>
        <span style="display: inline-block; width: 120px; font-weight: bold">Qty </span><?= $qty ?>

        <form class="w3-margin-top" action="send-to.php" method="post">

            <input hidden value="<?= $i ?>" id="tid" name="tid">
            <input hidden value="<?= $w ?>" id="w" name="w">
            <input hidden value="<?= $a ?>" id="aid" name="aid">
            <input hidden value="<?= $qty ?>" id="qty" name="qty">
            <input hidden value="<?= $pi ?>" id="trid" name="trid">


            <button class="w3-button w3-blue-grey w3-block" style="margin-top: 20px" type="submit">Submit</button>
        </form>

    </div>

</body>
</html>
