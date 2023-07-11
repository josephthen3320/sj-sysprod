<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnProduction();

$title = "Delete Worksheet";

$msg = "<span></span>";

$query_id               = $_GET['id'];
$current_id             = $wd_id = $query_id;
$current_sql            = "SELECT * FROM worksheet_detail WHERE id = $query_id";
$current_result         = $conn->query($current_sql);
$current_data           = mysqli_fetch_assoc($current_result);

$worksheet_id = $current_data['worksheet_id'];

$sql        = "SELECT id FROM worksheet WHERE worksheet_id = '$worksheet_id'";
$result     = $conn->query($sql);
$ws_id      = $result->fetch_assoc()['id'];

$cur_worksheet_id = $current_data['worksheet_id'];

// echo "<br><br><br><Br>{$cur_worksheet_id}<br>{$cur_worksheet_name}";

?>


<html>

<head>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?></title>

        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/css/w3.css">
        <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>

    <style>
        html {
            overflow: hidden;
        }
    </style>

<body>

    <div class="w3-top w3-bar w3-red">
        <span class="w3-bar-item"><?php echo $title; ?></span>
    </div>

    <div class="w3-container w3-padding-64">

        <h3 class="w3-text-red w3-center"><b>CONFIRM DELETE?</b></h3>


        <form method="POST" action="/worksheet/php/delete-worksheet.php">

            <div class="w3-container">
                <span class="w3-small">Internal ID: <?= $ws_id . " || " . $wd_id ?></span>
                <hr>

                    <label class="w3-small">Worksheet ID:</label>
                    <input value="<?php echo $cur_worksheet_id ?>"
                           type="text" class="w3-input w3-border" readonly name="worksheet_id">

                    <input hidden value="<?= $ws_id ?>" name="ws_id">
                    <input hidden value="<?= $wd_id ?>" name="wd_id">
                </div>

                <input type="submit" class="w3-button w3-quarter w3-red w3-hover-green w3-padding-16" style="margin-top: 16px;" value="Confirm">
            </div>
        </form>

    </div>
</body>



</html>

<script>
    function limitLength(input, maxLength) {
        if (input.value.length > maxLength) {
            input.value = input.value.slice(0, maxLength);
        }
    }
</script>