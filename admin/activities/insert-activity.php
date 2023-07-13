<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Table</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>
<script src="/assets/js/utils.js"></script>
<body>

<?php
    session_start();
    $uid = $_SESSION['user_id'];

    $msg = "Please input activity detail";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
        include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/agents/logging.php';
        $conn = getConnLog();

        // GET FORM DATA
        $id = $_POST['id'];
        $name = $_POST['name'];
        $name = strtoupper($name);

        $sql = "INSERT INTO activities (id, activity) VALUES ('$id', '$name')";

        try {
            $conn->query($sql);
            $msg = "<span class='w3-text-green'>";
            $msg = "Insert successful";
            $msg .= "</span>";
            logGeneric($uid, 13, "ACTIVITY TYPE CREATED; activity_id={$id}; activity_name={$name}");
        } catch (Exception $e) {
            $msg = "<span class='w3-text-red'>";
            $msg .= "Error: " . $e->getMessage();
            $msg .= "</span>";
        }

        $conn->close();


    }


?>


<div class="w3-container classification-content" id="worksheet-modal" style="">

    <h4>New Activity Type</h4>
    <span><?= $msg ?></span>

    <form class="w3-margin-top" action="" method="POST">
        <input class="w3-input w3-border" type="number" id="id" name="id" placeholder="Activity ID">
        <input class="w3-input w3-border" type="text" id="name" name="name" placeholder="Activity Name">
        <button class="w3-button w3-blue w3-bar w3-margin-top" type="submit">Submit</button>
    </form>

</div>

</body>
</html>