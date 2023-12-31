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
    <title>Send to Finishing</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>

<body>

    <div class="w3-container">
        <h1>Send to Finishing</h1>
        <span style="display: inline-block; width: 120px; font-weight: bold">Worksheet ID: </span><?= $w ?>
        <br>
        <span style="display: inline-block; width: 120px; font-weight: bold">Cutting ID: </span><?= $pi ?>
        <br>
        <span style="display: inline-block; width: 120px; font-weight: bold">Article ID: </span><?= $a ?>
        <br><br>
        <span style="display: inline-block; width: 120px; font-weight: bold">Qty </span><?= $qty ?>

        <form class="w3-margin-top" action="send-to.php" method="post">

            <input hidden value="<?= $i ?>" id="tid" name="tid">
            <input hidden value="<?= $w ?>" id="w" name="w">
            <input hidden value="<?= $a ?>" id="aid" name="aid">
            <input hidden value="<?= $qty ?>" id="qty" name="qty">
            <input hidden value="<?= $pi ?>" id="trid" name="trid">

            <?php
            require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
            require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_classification.php';
            require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

            $conn = getConnProduction();
            $cmts = fetchCMTs();

            echo "<input hidden value='{$i}' name='uid'>";

            echo "<input hidden value='{$w}' name='w'>";            // Worksheet ID
            echo "<input hidden value='{$i}' name='tid'>";          // Process internal ID
            echo "<input hidden value='{$pi}' name='trid'>";        // Process ID
            echo "<input hidden value='{$qty}' name='qty'>";        // qty to send

            $aid = fetchWorksheetData($w)['article_id'];

            echo "<input hidden value='{$aid}' name='aid'>";        // article id

            echo "  <label>Select location: </label>
                    <select class=\"w3-select w3-border\" required id=\"cmt\" name=\"cmt\">
                    ";

            while ($cmt = $cmts->fetch_assoc()) {
                if ($cmt['cmt_type'] === 'CT3') {
                    echo '<option value="' . $cmt['cmt_id'] . '">' . $cmt['cmt_name'] . '</option>';
                }
            }


            echo "</select>";


            ?>


            <button class="w3-button w3-blue-grey w3-block" style="margin-top: 20px" type="submit">Submit</button>
        </form>

    </div>

</body>
</html>
