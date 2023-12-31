<?php
session_start();
$uid = $_SESSION['user_id'];

$closeWindowScript = "<script type='text/javascript'>window.close();</script>";

if (!isset($_GET)) { echo $closeWindowScript; }

$w = $_GET['w'];
$i = $_GET['i'];
$pi = $_GET['pi'];
$a = $_GET['a'];


?>


<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send to Cutting</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>

<body>

    <div class="w3-container">
        <h1>Send to cutting</h1>
        <span style="display: inline-block; width: 120px; font-weight: bold">Worksheet ID: </span><?= $w ?>
        <br>
        <span style="display: inline-block; width: 120px; font-weight: bold">Pola Marker ID: </span><?= $pi ?>
        <br>
        <span style="display: inline-block; width: 120px; font-weight: bold">Article ID: </span><?= $a ?>

        <form class="w3-margin-top" action="send-to.php" method="post">

            <input hidden value="<?= $i ?>" id="tid" name="tid">
            <input hidden value="<?= $w ?>" id="w" name="w">
            <input hidden value="<?= $a ?>" id="aid" name="aid">
            <input hidden value="<?= $pi ?>" id="trid" name="trid">

            <label>Select location for cutting:</label>
            <select class="w3-select w3-border" required id="cmt" name="cmt">

                <!-- TODO: make php fetch cmt cutting CT1 -->
                <option disabled>Please select</option>

                <?php
                    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
                    require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_classification.php';

                    $cmt_conn = getConnProduction();

                    $cmt_data = fetchAllCMTByType('CT1');
                    while ($cmt = $cmt_data->fetch_assoc()) {

                        echo "<option value='{$cmt['cmt_id']}'>{$cmt['cmt_name']}</option>";

                    }



                ?>
            </select>

            <button class="w3-button w3-blue-grey w3-block" style="margin-top: 20px" type="submit">Submit</button>
        </form>

    </div>

</body>
</html>
