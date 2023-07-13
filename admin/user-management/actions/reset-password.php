<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/agents/logging.php";      // Logging agent
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/agents/emailer/emailer.php";      // Logging agent

if (isset($_GET['u'])) {
    $username = $_GET['u'];

    $sql = "SELECT email FROM user_accounts WHERE username = '$username'";
    $conn = getConnUser();
    $result = $conn->query($sql);
    $email = $result->fetch_assoc()['email'];


    //$email = "joseph.then@suburjayabdg.com";

}

if (isset($_POST['token'])) {
    $conn = getConnUser();


    $username = $_POST['username'];
    $token = $_POST['token'];
    $mailRecepient = $_POST['email'];

    // Log reset request == 6
    logGeneric(getUserIdByUsername($username), 6, "REQUEST ACCOUNT RESET;");

    // Get current datetime and calculate expiry date
    $nowDate = new DateTime();
    $expiryDate = clone $nowDate;
    $expiryDate->modify('+2 hours');

    // Format the dates as strings
    $nowDateString = $nowDate->format('Y-m-d H:i:s');
    $expiryDateString = $expiryDate->format('Y-m-d H:i:s');

    $sql = "UPDATE password_reset_token SET expiry_date = '$nowDateString', is_expired = 1 WHERE username = '$username' AND is_expired = 0";
    $conn->query($sql);

    $sql = "INSERT INTO password_reset_token (username, token, request_date, expiry_date) VALUES ('$username', '$token', '$nowDateString', '$expiryDateString')";

    $nowDateString . "<BR>" . $expiryDateString;
    $conn->query($sql);

    $sql = "SELECT id FROM password_reset_token WHERE token='$token'";
    $result = $conn->query($sql);
    $reset_id = $result->fetch_assoc()['id'];

    // Log token generation == 7
    logGeneric(-1, 7, "GENERATED RESET TOKEN; resetId={$reset_id};");

    // Log reset operator == 6
    logGeneric($_SESSION['user_id'], 6, "STARTED ACCOUNT RESET; resetId={$reset_id};");

    $conn->close();

    // echo '<script>window.close();</script>';
    $href = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/user-management/reset-password.php?token=' . $token;

    echo "<b>To: </b>{$username}@example.com<br>";
    echo "<b>From:</b>admin@example.com<br><br>";

    echo "<b>Subject:</b> Password Reset Requested<br><br>";

    $mailContent = "You have requested a password reset. Please click <a href='{$href}'>here</a> ";
    $mailContent .= "<br>or copy and paste the following link to your browser.<br><br>";
    $mailContent .= "<a href='{$href}'>";
    $mailContent .= $href;
    $mailContent .="</a>";

    echo $mailContent;

    sendNoReplyEmail("Password Reset Requested", $mailContent, $mailRecepient);



    exit;

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password for <?= $username ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>
<body>

<div class="w3-display-container" style="min-height: 100vh">
    <div class="w3-display-middle">

        <h5>Password reset confirmation</h5>
        <span class="w3-small">Generate token for:</span>

        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input class="w3-input" readonly name="username" value="<?= $username ?>">
            <input class="w3-input" readonly name="email" value="<?= $email ?>">
            <input readonly hidden name="token" value="<?= bin2hex(random_bytes(32)) ?>">

            <button class="w3-button w3-bar w3-blue-grey" type="submit" style="margin-top: 64px;">Generate link</button>
        </form>
    </div>
</div>

<div class="w3-bar w3-blue-grey w3-top">
    <span class="w3-bar-item">Password reset</span>
</div>




</body>