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
    <title>File Upload Form</title>
</head>
<body>
    <form action="upload-file-worksheet.php" method="post" enctype="multipart/form-data">
        <label for="file">Select a spreadsheet file:</label>
        <input type="file" name="file" id="file">
        <br>
        <label for="filename">Enter the filename:</label>
        <input type="text" name="filename" id="filename" value="<?= $worksheet_id ?>">
        <br>
        <input type="submit" name="submit" value="Upload">
    </form>
</body>
</html>
