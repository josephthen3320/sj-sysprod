<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnProduction();

$title = "Generate Worksheet";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql        = "SELECT * FROM worksheet_detail WHERE id = $id";
    $result     = $conn->query($sql);
    $row        = $result->fetch_assoc();

}
/*
$order              = $_GET['o'];
$fabric_utama       = $_GET['fu'];
$cloth              = $_GET['cw'];
$general_est_cons   = $_GET['gec'];
$embro              = $_GET['e'];
$print              = $_GET['p'];
*/
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title . ": " . $row['worksheet_id'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
    <style>
        body {
            /*background-color: #F4EDE9;*/
        }
    </style>
</head>

<body>
<div class="w3-top w3-bar w3-blue-gray">
    <span class="w3-bar-item"><?= $title ?></span>
</div>

<div class="w3-container w3-padding-64">

    <h3><?= $row['worksheet_id'] ?></h3>

    <h5>Add Information:</h5>
    <form action="generate-worksheet.php">
        <input hidden value="<?= $id ?>" name="id">
        <input hidden value="i" name="wstype">

        <div class="w3-row-padding">
            <div class="w3-third">
                <label for="o">Order</label>
                <input type="text" name="o" id="o" placeholder="Order" class="w3-input w3-border">
            </div>
            <div class="w3-third">
                <label for="fu">Fabric utama</label>
                <input type="text" name="fu" id="fu" placeholder="Fabric utama" class="w3-input w3-border">
            </div>
            <div class="w3-third">
                <label for="cw">Lebar Kain</label>
                <input type="text" name="cw" id="cw" placeholder="Lebar Kain" class="w3-input w3-border">
            </div>
        </div>
        <div class="w3-row-padding w3-padding-16">
            <div class="w3-third">
                <label for="p">Print</label>
                <input type="text" name="p" id="p" placeholder="Print" class="w3-input w3-border">
            </div>
            <div class="w3-third">
                <label for="e">Embro</label>
                <input type="text" name="e" id="e" placeholder="Embro" class="w3-input w3-border">
            </div>
            <div class="w3-third">
                <label for="gec">General Est. Cons</label>
                <input type="text" name="gec" id="gec" placeholder="General Est. Cons" class="w3-input w3-border">
            </div>
        </div>

        <div class="w3-padding-16">
            <div class="w3-half w3-padding">
                <button class="w3-button w3-blue w3-padding-16 w3-round-large" type="submit" style="width: 100%;">Internal &nbsp;&nbsp; <i class="fa-solid fa-right-to-bracket"></i></button>

            </div>
            <div class="w3-half w3-padding">
                <div class="w3-button w3-red w3-padding-16 w3-round-large" onclick="redirectWindow('generate-worksheet.php?id=<?=$id?>&wstype=e')" style="width: 100%;">External &nbsp;&nbsp; <i class="fa-solid fa-right-from-bracket"></i></div>
            </div>

        </div>
    </form>


</div>

<div class="w3-bar w3-blue-grey w3-bottom" style="">
    <div class="w3-container w3-bar-item">
        <span class="w3-bar-item">xx</span>
    </div>
</div>

</body>
</html>

<script src="/assets/js/popup-timeout.js"></script>

<script>
    function redirectWindow(url) {
        window.location.href = url;
    }
</script>
