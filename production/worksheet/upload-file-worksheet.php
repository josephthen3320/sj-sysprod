<?php
session_start();
$uid = $_SESSION['user_id'];
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/agents/logging.php";

$closeWindowScript = "<script type='text/javascript'>window.close();</script>";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && isset($_POST['filename'])) {
    $targetDirectory = 'files/';
    $targetFile = $targetDirectory . basename($_FILES['file']['name']);
    $filename = $_POST['filename'];

    // Check if the file is a valid spreadsheet (e.g., Excel)
    $allowedExtensions = ['xls', 'xlsx'];
    $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    if (in_array($fileExtension, $allowedExtensions)) {
        // Save the file with the specified filename
        $newFilePath = $targetDirectory . $filename . '.' . $fileExtension;
        move_uploaded_file($_FILES['file']['tmp_name'], $newFilePath);
        echo "File uploaded successfully!";

        $details = "WORKSHEET FILE IMPORTED; filename=$filename;";
        logGeneric($uid, 428, $details);

        echo $closeWindowScript;
    } else {
        echo "Invalid file format. Only spreadsheet files (xls, xlsx) are allowed.";
    }
}
?>
