<?php
require_once "php-modules/db.php";
$conn = getConnUser();

session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/agents/logging.php';

$sso_name           = "SentinelAuth";
$sso_version        = 1.2;
$sso_text           = $sso_name . " SSO v." . $sso_version;

$error = "";

if (isset($_SESSION['error'])) {
    echo $error .= $_SESSION['error'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $u_username = $_POST['username'];

    // check if user exist and blocked
    $sql = "SELECT * FROM user_accounts WHERE username='$u_username'";
    $result = $conn->query($sql);

    // Check if username exist
    if ($result->num_rows == 0) {       // user doesn't exist
        // Log login attempt
        $uag = getClientUserAgentComma();
        logGeneric(0, 11, "LOGIN FAILED; USERNAME NOT FOUND; username={$u_username}; uag=[{$uag}]");
        $_SESSION['error'] = "Invalid login, please try again.";
        header('Location: login.php');
        exit();
    }

    // Fetch user data
    $row = $result->fetch_assoc();

    // Check if user is locked ? redirect back to login : continue
    if ($row['is_locked'] == 1) {       // user locked
        $uag = getClientUserAgentComma();
        logGeneric(-1, 11, "LOGIN BLOCKED; ACCOUNT DISABLED; username={$row['username']}; uag=[{$uag}]");
        $_SESSION['error'] = "Account locked, please contact IT Services.";
        header('Location: login.php');
        exit();
    }

    // Check password match
    $hashedPassword = hash('sha256', $_POST['password']);
    if ($row['password'] != $hashedPassword) {
        $uag = getClientUserAgentComma();
        logGeneric($row['id'], 11, "LOGIN FAILED; PASSWORD MISMATCH; username={$row['username']}; uag=[{$uag}]");
        $_SESSION['error'] = "Invalid login, please try again.";
        header('Location: login.php');
        exit();
    }

    // All checks passed
    // Login success

    //logLogin($row['id'], 0, $row['username']);
    logGeneric($row['id'], 11, "LOGIN SUCCESS; username={$row['username']}; sessionId=".getSessionId()."; uag=[".getClientUserAgentComma()."]");

    $_SESSION["username"] = $row['username'];
    $_SESSION["user_id"] = $row['id'];
    $_SESSION['user_role'] = $row['role'];
    header('Location: dashboard');
    exit();
}


// Check if the user is already logged in
if (isset($_SESSION["username"])) {
    header("Location: dashboard");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CV Subur Jaya</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="assets/fontawesome/css/all.css" type="text/css">

    <script src="/assets/js/utils.js"></script>

    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            background-color: #0B293C;
        }

        form {
        }

        input[type="text"],
        input[type="password"] {
            width: 75%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #D50B08;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #c30907;
        }

        .error-message {
            color: red;
        }
    </style>
</head>
<body class="w3-display-container" style="height: 100vh">

    <div class="w3-display-middle">
        <div class="w3-container w3-center w3-round-large w3-card-4 w3-white w3-hide-large w3-hide-medium" style="width: 75vw">
            <div class="w3-padding-16">
                <img src="assets/logo/SJ_Logo.png" width="25%">
                <h4 class="w3-xlarge">Login Portal</h4>
            </div>

            <div class="w3-margin-bottom">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <?php if (isset($error)) { ?>
                        <p class="error-message w3-small"><?php echo $error; ?></p>
                    <?php } ?>
                    <input type="text" name="username" placeholder="Username" required style="width: 100%; height: 56px">
                    <input type="password" name="password" placeholder="Password" required style="width: 100%; height: 56px">
                    <br><br>
                    <input class="w3-button w3-red" type="submit" value="Login" style="width: 100%; height: 56px">
                </form>
            </div>

            <div class="w3-bar w3-container">
                <p class="w3-tiny"><?php echo $sso_text; ?></p>
            </div>
        </div>


        <div class="w3-container w3-round w3-card-4 w3-center w3-white w3-hide-small">
            <div class="w3-container w3-padding-16">
                <img src="assets/logo/SJ_Logo.png" width="25%">
                <h4>Login Portal</h4>
            </div>

            <div class="w3-margin-bottom">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <?php if (isset($error)) { ?>
                        <p class="error-message w3-small"><?php echo $error; ?></p>
                    <?php } ?>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <br><br>
                    <input class="w3-button w3-red" type="submit" value="Login" style="width: 75%;">
                </form>
            </div>

            <div class="w3-margin-bottom">
                <span onclick="openModal()" class="w3-small w3-hover-text-blue-grey" style="text-decoration: none; cursor: pointer;">Forgot Password?</span>
            </div>


            <div class="w3-bar w3-container">
                <p class="w3-tiny"><?php echo $sso_text; ?></p>
            </div>
        </div>
    </div>

    <!-- todo: Finish this -->
    <div class="w3-display-container" id="forgotPasswordModal" style="display: none; width: 100vw; height: 100vh; position: absolute; left:0; top:0; background-color: rgba(0, 0, 0, .5);">
        <div class="w3-display-middle w3-card-4 w3-container w3-display-container" style="padding: 32px 64px; background-color: rgba(251, 251, 251, 1)">
            <button onclick="closeModal()" class="w3-button w3-red w3-display-topright"><i class="fas fa-x"></i></button>
            <h4>Forgot Password?</h4>
            <span class="w3-small">A request for password change will be submitted to ITS.</span><br>
            <span class="w3-small">Please submit your username.</span>

            <form class="w3-margin-top" action="#" method="post">
                <input class="w3-input w3-border" placeholder="Username" name="username" required>
                <span class="w3-button w3-margin-top w3-margin-bottom w3-blue-grey w3-bar">Submit</span>
            </form>
        </div>
    </div>


<footer class="w3-bottom w3-bar w3-center w3-padding-16 w3-tiny w3-text-white">
    &copy; 2023 CV Subur Jaya. All rights reserved.
</footer>

</body>
</html>

<script>
    function closeModal() {
        var modal = document.getElementById('forgotPasswordModal');
        modal.style.display = 'none';
    }
    function openModal() {
        var modal = document.getElementById('forgotPasswordModal');
        modal.style.display = 'block';
    }
</script>