<?php
session_start();
$uid = $_SESSION['user_id'];

$closeWindowScript = "<script type='text/javascript'>window.close();</script>";

if (!isset($_GET)) { echo $closeWindowScript; }

$id = $_GET['i'];
$cutting_id = $_GET['c'];
$worksheet_id = $_GET['w'];

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
        <h1>Add cutting out qty</h1>
        <p><strong><?= $cutting_id ?></strong></p>

        <form action="submit-qty-out.php" method="post">
            <input hidden value="<?= $id ?>" id="id" name="id">
            <input hidden value="<?= $cutting_id ?>" id="tid" name="tid">
            <input hidden value="<?= $worksheet_id ?>" id="wid" name="wid">
            <label>Qty hasil cutting: </label>
            <input type="number" min="0" id="cutting_out" name="cutting_out">
            <button type="submit">Submit</button>
        </form>
    </div>

</body>
</html>