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
    <title>Send to</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>

<body>

    <div class="w3-container">
        <h1>Send to Washing</h1>
        <br><br>
        <span style="display: inline-block; width: 120px; font-weight: bold">Worksheet ID: </span><?= $w ?>
        <br>
        <span style="display: inline-block; width: 120px; font-weight: bold">Transit ID: </span><?= $pi ?>
        <br>
        <span style="display: inline-block; width: 120px; font-weight: bold">Article ID: </span><?= $a ?>
        <br><br>
        <span style="display: inline-block; width: 120px; font-weight: bold">Qty </span><?= $qty ?>

        
        <!-- TODO: Handling for accepted/rejected/service/others -->
        
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

            echo "  <label>Select location: </label>
                    <select class=\"w3-select w3-border\" required id=\"cmt\" name=\"cmt\">
                        <option disabled hidden selected>Please select</option>
                    ";

            while ($cmt = $cmts->fetch_assoc()) {
                if ($cmt['cmt_type'] === 'CT6') {
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
