<?php
session_start();
$uid = $_SESSION['user_id'];

include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';

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
    <title>Send 'QC Embro out</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>

<body>

    <div class="w3-container">
        <h1>Send to <?= $processName ?></h1>
        <span style="display: inline-block; width: 120px; font-weight: bold">Worksheet ID: </span><?= $w ?>
        <br>
        <?php
        require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';
        $worksheetData = fetchWorksheetData($w);
        ?>
        <span style="display: inline-block; width: 120px; font-weight: bold">Article ID: </span><?= $worksheetData['article_id'] ?>
        <br>
        <span style="display: inline-block; width: 120px; font-weight: bold">QC Embro ID: </span><?= $pi ?>

        <!-- First form: Select target process -->
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
                        ";

                if (!checkIsProcessDone($w, 'print_sablon')) {
                    echo "<option value='EP'>Print/Sablon</option>";
                }

                echo "
                            <option value=\"SI\">Sewing (CMT)</option>
                        </select>

                        <button class=\"w3-button w3-red w3-block\" type=\"submit\">Load CMT List</button>

                    </form>
                ";
            }


        ?>

        <!-- Second Form: Select CMT -->
        <?php
            require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
            require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_classification.php';
            require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

            $conn = getConnProduction();
            $cmts = fetchCMTs();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo "<br><br><span style=\"display: inline-block; width: 120px; font-weight: bold\">Sending to:</span> $processName";
                $selectedProcess = $_POST['process'];

                echo "<form action='send-to.php' method='post'>";

                echo "<input hidden value='{$processName}' name='pname'>";    // Send process target (embro/print/sewing)

                echo "<input hidden value='{$i}' name='uid'>";

                echo "<input hidden value='{$w}' name='w'>";            // Worksheet ID
                echo "<input hidden value='{$i}' name='tid'>";          // Process internal ID
                echo "<input hidden value='{$pi}' name='trid'>";        // Process ID
                echo "<input hidden value='{$qty}' name='qty'>";        // qty to send

                $aid = fetchWorksheetData($w)['article_id'];

                echo "<input hidden value='{$aid}' name='aid'>";        // article id

                echo "  <label>Select location: </label>
                        <select class=\"w3-select w3-border\" required id=\"cmt\" name=\"cmt\">
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

                echo "</select>";
                echo "<button class=\"w3-button w3-blue-grey w3-block\" style=\"margin-top: 20px\" type=\"submit\">Submit</button>";
                echo "</form>";
            }

        ?>

    </div>

</body>
</html>
