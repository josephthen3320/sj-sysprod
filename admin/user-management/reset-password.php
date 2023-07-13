<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnUser();

if (isset($_POST['password'])) {

    include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/agents/logging.php';

    $username = $_POST['username'];
    $hashedPassword = hash('sha256', $_POST['password']);
    $token = $_POST['token'];

    $sql = "UPDATE user_accounts SET password = '$hashedPassword' WHERE username = '$username'";
    $conn->query($sql);

    $sql = "UPDATE password_reset_token SET is_expired = 1 WHERE token = '$token'";
    $conn->query($sql);

    $sql = "SELECT id FROM password_reset_token WHERE token = '$token'";
    $result = $conn->query($sql);
    $reset_id = $result->fetch_assoc()['id'];

    logGeneric(getUserIdByUsername($username), 6, "RESET ACCOUNT SUCCESS; resetId={$reset_id};");
    logGeneric(getUserIdByUsername($username), 2, "SELF PASSWORD UPDATE;");

    echo "Password Reset Success! Please login.";
    exit;
}

$sso_name = "SentinelAuth";
$sso_version = "1.0";
$sso_text = $sso_name . " SSO v." . $sso_version;

// TODO: Change this to actual user role
$user_role = "Kucing Admin";

if (!isset($_GET['token'])) { echo "No token provided."; exit; }

$token = $_GET['token'];

$sql        = "SELECT * FROM password_reset_token WHERE token = '$token'";
$result     = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Invalid token";
    exit;
}

$row = $result->fetch_assoc();


if ($row['is_expired']) {
    echo "Token expired, please request again";
    exit;
}

$expiry_date = $row['expiry_date'];
$expiryDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $expiry_date);

$nowDate = new DateTime();
$nowDateString = $nowDate->format('Y-m-d H:i:s');

if ($expiryDateTime <= $nowDate) {
    echo "Token expired, please request again";
    $sql = "UPDATE password_reset_token SET is_expired = 1 WHERE token = '$token'";
    $conn->query($sql);
    exit;
}

$username = $row['username'];

$top_title = "User Management";
if ($username == "nara") {
    $top_title .= "";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $top_title ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">

    <style>
        body {
            background-color: #F4EDE9;
        }
        .jt-orange {
            background-color: #ff5722;
        }
        .fa-4xl {
            font-size: 3em;
            line-height: 0.01637em;
            vertical-align: -0.27679em;
        }
    </style>
</head>
<body>

<div class="w3-display-container w3-row" style="min-height: 100vh; background-color:">
    <div class="w3-display-middle w3-col l4 m6 s8">
        <div class="w3-border" style="background-color: #fff; padding: 0 64px;">
            <div class="w3-padding-top-24">
                <h2 class="w3-bar-item" style="font-weight: 400;">Reset Password</h2>
            </div>
            <div class="w3-border-top" style="padding-top: 5px">
                <span class="w3-small">Please enter your new password.</span>

                <form class="w3-margin-bottom" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <input type="hidden" name="username" value="<?= $username ?>">

                    <div class="w3-padding-16">
                        <label class="" for="password">New Password:</label>
                        <input class="w3-input w3-border" type="password" id="password" name="password" required>
                    </div>

                    <div class="w3-padding-16">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input class="w3-input w3-border" type="password" id="confirm_password" name="confirm_password" required>
                    </div>

                    <input class="w3-bar w3-button w3-blue-grey" style="margin-top: 32px;"
                           type="submit" value="Reset Password">
                </form>

                <div class="w3-rest w3-center">
                    <span class="w3-small">
                        Do not share this link to anyone!
                        <?= $token = bin2hex(random_bytes(32));?>

                        <!--If you didn't make this request, please
                        <a class="w3-text-blue-grey" href="report.php" style="text-decoration: none;">
                            <b>report</b>
                        </a>
                        -->
                    </span>
                </div>
            </div>


            <div class="w3-bar w3-container w3-center w3-padding">
                <p class="w3-tiny"><?php echo $sso_text; ?></p>
            </div>
        </div>
    </div>


</div>

</body>
</html>
