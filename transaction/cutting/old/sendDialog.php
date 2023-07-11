<?php
session_start();
$uid = $_SESSION['user_id'];

$closeWindowScript = "<script type='text/javascript'>window.close();</script>";

$processName = '';
if (!isset($_GET)) { echo $closeWindowScript; }

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $w  = $_GET['w'];
    $i  = $_GET['i'];
    $pi = $_GET['pi'];
    $qty = $_GET['q'];
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $w  = $_POST['w'];
    $i  = $_POST['i'];
    $pi = $_POST['pi'];
    $qty = $_POST['qty'];

    if ($_POST['process'] === "EB") {
        $processName = 'Embro';
    } elseif ($_POST['process'] === "EP") {
        $processName = 'Print/Sablon';
    } elseif ($_POST['process'] === "SI") {
        $processName = 'Sewing';
    } else {
        $processName = '<span class="w3-text-red">Unknown</span>';
    }
}


?>


<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send cutting out</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>

<body>

    <div class="w3-container">
        <h1>Send to <?= $processName ?></h1>
        <span style="display: inline-block; width: 120px; font-weight: bold">Worksheet ID: </span><?= $w ?>
        <br>
        <span style="display: inline-block; width: 120px; font-weight: bold">Cutting ID: </span><?= $pi ?>

        <?php
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                echo "
                    <form class='w3-margin-top' action=\"{$_SERVER['PHP_SELF']}\" method=\"POST\">

                        <input hidden value=\"{$i}\" id=\"i\" name=\"i\">
                        <input hidden value=\"{$w}\" id=\"w\" name=\"w\">
                        <input hidden value=\"{$pi}\" id=\"pi\" name=\"pi\">
                        <input hidden value=\"{$qty}\" id=\"qty\" name=\"qty\">

                        <label>Select process:</label>
                        <select class=\"w3-select w3-border w3-margin-bottom\" required id=\"process\" name=\"process\">
                            <option selected hidden disabled>Please select</option>
                            <option value=\"EB\">Embro</option>
                            <option value=\"EP\">Print/Sablon</option>
                            <option value=\"SI\">Sewing (CMT)</option>
                        </select>

                        <button class=\"w3-button w3-red w3-block\" type=\"submit\">Load CMT List</button>

                    </form>
                ";
            }


        ?>

        <?php
        //send-to-cutting.php
            require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db_combined.php';
            require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_classification.php';
            require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

            $conn = getConnProduction();
            $cmts = fetchCMTs();

            // TODO: dynamic <option> based on the 'process' select.

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo "<br><br><span style=\"display: inline-block; width: 120px; font-weight: bold\">Sending to:</span> $processName";
                $selectedProcess = $_POST['process'];

                echo ">";

                echo "<input hidden value='{$processName}' name='pname'>";    // Send process target (embro/print/sewing)

                echo "<input hidden value='{$i}' name='uid'>";

                echo "<input  value='{$w}' name='w'>";        // Worksheet ID
                echo "<input  value='{$i}' name='tid'>";    // Process internal ID
                echo "<input  value='{$pi}' name='trid'>";    // Process ID
                echo "<input  value='{$qty}' name='qty'>";   // qty to send

                $aid = fetchWorksheetData($w)['article_id'];

                echo "<input  value='{$aid}' name='aid'>";   // qty to send



                echo "  <label>Select location: </label>
                        <select class=\"w3-select w3-border\" required id=\"cmt\" name=\"cmt\">
                            <!-- TODO: make php fetch cmt cutting CT1 -->
                            <option disabled hidden selected>Please select</option>
                        ";

                while ($cmt = $cmts->fetch_assoc()) {
                    if ($selectedProcess === 'EB' || $selectedProcess === 'EP') {
                        if ($cmt['cmt_type'] === 'CT2') {
                            echo '<option value="' . $cmt['cmt_id'] . '">' . $cmt['cmt_name'] . '</option>';
                        }
                    } else if ($selectedProcess === 'SI') {
                        if ($cmt['cmt_type'] === 'CT5') {
                            echo '<option value="' . $cmt['cmt_id'] . '">' . $cmt['cmt_name'] . '</option>';
                        }
                    }

                }

                    // if 'process' === 'EB' or 'EP', print <option value='$cmt['cmt_id']>$cmt['cmt_name']</option> WHERE $cmt['cmt_type'] = 'CT2'
                    // if 'process' === 'SI', print <option value='$cmt['cmt_id']>$cmt['cmt_name']</option> WHERE $cmt['cmt_type'] = 'CT5'

                echo "</select>";
                echo "<button class=\"w3-button w3-blue-grey w3-block\" style=\"margin-top: 20px\" type=\"submit\">Submit</button>";
                echo "</form>";
            }

        ?>

    </div>

</body>
</html>
