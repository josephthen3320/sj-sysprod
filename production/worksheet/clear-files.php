<?php
session_start();
$role = $_SESSION['user_role'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $folderPath = 'files/';

// Get all files and directories inside the folder
    $files = glob($folderPath . '*');

// Loop through each file and directory
    foreach ($files as $file) {
        if (is_file($file)) {
            // Delete the file
            unlink($file);
        } elseif (is_dir($file)) {
            // Delete the directory and its contents recursively
            deleteDirectory($file);
        }
    }

    echo "All contents inside the folder have been cleared.";
}

// Function to delete a directory and its contents recursively
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return;
    }

    $files = array_diff(scandir($dir), array('.', '..'));

    foreach ($files as $file) {
        if (is_dir("$dir/$file")) {
            deleteDirectory("$dir/$file");
        } else {
            unlink("$dir/$file");
        }
    }

    rmdir($dir);
}

if ($role != 0) {
    header("Location: /");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Clear files: Worksheet</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>

<body class="w3-display-container w3-red" style="height: 100vh;">


    <form action="" method="post" class="w3-display-middle">
        <i class="fas fa-fw fa-warning fa-5x"></i><br><br>
        <span class="w3-xxxlarge w3-text-white" style="font-weight: bold;"> WARNING</span>

        <h6 class="w3-text-white w3-small">
            This action will delete all uploaded worksheet files, and <u>cannot</u> be undone!
        </h6>

        <button class="w3-button w3-border w3-hover-pale-red w3-border-white w3-right w3-padding" type="submit" style="margin-top: 32px; font-weight: bold;">CONFIRM</button>
    </form>


</body>
</html>

