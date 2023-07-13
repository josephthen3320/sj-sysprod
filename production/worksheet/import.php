<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet.php";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
	$id = $_GET['id'];
	$worksheet_id = getWorksheetIdByGlobalId($id);
	
	
}	

?>

<!DOCTYPE html>
<html>
<head>
    <title><?= "Upload File Worksheet: " . $worksheet_id ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>
<body>

    <div class="w3-top w3-bar w3-blue-gray">
        <span class="w3-bar-item">Upload File: Worksheet</span>
    </div>

    <div class="w3-container w3-padding-64">
        <h3>Upload: <?= $worksheet_id ?></h3>
        <p class="w3-small">File yang diunggah akan menggantikan file yang sudah ada.<br>
            <span class="w3-text-red">Pastikan file yang dipilih sudah benar!</span></p>

        <form class="w3-margin-top w3-padding-16" action="upload-file-worksheet.php" method="post" enctype="multipart/form-data">
            <label for="file">Pilih file worksheet (.xlsx):</label>
            <input class="w3-input" type="file" name="file" id="file" required>
            <br>
            <span>Filename: </span><span class="w3-monospace"><?= $worksheet_id . ".xlsx" ?></span><br>
            <input type="hidden" name="filename" id="filename" value="<?= $worksheet_id ?>">

            <input class="w3-button w3-blue w3-margin-top w3-bar" type="submit" name="submit" value="Upload">
        </form>

    </div>


</body>
</html>
