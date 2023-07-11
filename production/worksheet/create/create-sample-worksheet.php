<?php
session_start();
$userId = $_SESSION['user_id'];

$root = $_SERVER['DOCUMENT_ROOT'];

include $root . "/php-modules/db.php";
$conn = getConnProduction();
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet_position.php";



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $n = $_POST['nRecord'];
    $i = 0;

    while ($i != $n) {
        ++$i;
        $sql = "SELECT article_id FROM article ORDER BY RAND() LIMIT 1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo $article_id = $row['article_id'];
        } else {
            echo "No records found.";
        }

        if (!$article_id) {
            $_SESSION['msg'] = "Error: Article cannot be empty.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        $worksheet_id = generateWorksheetId();
        $delivery_date = generateRandomDate();
        $po_date = generateRandomDate();

        $po_date = !$po_date ? null : $po_date;

        $sql = "INSERT INTO worksheet (worksheet_id, delivery_date, po_date, created_by) VALUES ('$worksheet_id', '$delivery_date', '$po_date', '$userId')";
        $conn->query($sql);

        $qty = rand(10, 9999);

        $sql = "SELECT customer_id FROM customer ORDER BY RAND() LIMIT 1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo $customer_id = $row['customer_id'];
        } else {
            echo "No records found.";
        }

        $cloth_width = rand(0, 300);
        $isFob = rand(0, 1);
        $description = generateRandomSentence();


        $sql = "INSERT INTO worksheet_detail (worksheet_id, article_id, qty, customer_id, cloth_width, is_fob, description)
                 VALUES ('$worksheet_id', '$article_id', '$qty', '$customer_id', '$cloth_width', '$isFob', '$description')";
        $conn->query($sql);

        updateWorksheetPosition($worksheet_id, 0);
    }


    $conn->close();
    exit();
} else {

}


//$_SESSION['success_msg'] = "Worksheet created!";
// header("LOCATION: " . $_SERVER['HTTP_REFERER']);

// Function to generate a random date within a range
function generateRandomDate() {
    $startDate = strtotime('-1 year'); // Start date - adjust as needed
    $endDate = strtotime('+1 year'); // End date - adjust as needed

    // Generate a random timestamp within the date range
    $randomTimestamp = mt_rand($startDate, $endDate);

    // Format the timestamp as a date
    $randomDate = date('Y-m-d', $randomTimestamp);
    return $randomDate;
}

function generateRandomId() {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $id = '';

    for ($i = 0; $i < 12; $i++) {
        $id .= $characters[rand(0, strlen($characters) - 1)];
        if ($i == 3 || $i == 6) {
            $id .= '-';
        }
    }

    return $id;
}

function generateRandomSentence() {
    $subjects = array("Gleeb", "Zorplin", "Quixlez", "Flumf", "Blork");
    $verbs = array("glurps", "bopple", "snorps", "zibbles", "crumples");
    $objects = array("frazzles", "mibbles", "drazzles", "nibbles", "plork");

    $randomSubject = $subjects[array_rand($subjects)];
    $randomVerb = $verbs[array_rand($verbs)];
    $randomObject = $objects[array_rand($objects)];

    $randomSentence = ucfirst($randomSubject) . " " . $randomVerb . " " . $randomObject . ".";

    return $randomSentence;
}

$nToGenerate = 5;

?>

<html>
<head>
    <link rel="stylesheet" href="/assets/css/w3.css">
</head>

<body class="w3-padding">

<div class="w3-bar w3-monospace w3-text-red">
    <b>Debugging mode</b>
</div>

<h6 class="w3-monospace">Populating table with <?= $nToGenerate ?> random records.</h6>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <input type="hidden" name="nRecord" value="<?= $nToGenerate ?>">
    <button class="w3-button w3-bar w3-light-grey" type="submit">Confirm</button>
</form>

</body>
