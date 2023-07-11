<?php
session_start();

$title = "Delete Worksheet";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/agents/logging.php";

    // Suburjaya_worksheet DB actions
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnWorksheet();
    
    $ws_id = $_POST['ws_id'];
    $wd_id = $_POST['wd_id'];

    $worksheet_id = $_POST['worksheet_id'];

    $sql_wsdb = "DELETE FROM material_accessories WHERE worksheet_id = '$worksheet_id'";
    $conn->query($sql_wsdb);
    $sql_wsdb = "DELETE FROM material_body WHERE worksheet_id = '$worksheet_id'";
    $conn->query($sql_wsdb);
    $sql_wsdb = "DELETE FROM qty_size WHERE worksheet_id = '$worksheet_id'";
    $conn->query($sql_wsdb);
    $sql_wsdb = "DELETE FROM size_spec WHERE worksheet_id = '$worksheet_id'";
    $conn->query($sql_wsdb);
    $sql_wsdb = "DELETE FROM test_susut WHERE worksheet_id = '$worksheet_id'";
    $conn->query($sql_wsdb);

    $conn->close();

    // Suburjaya_production DB actions
    $sql_ws = "DELETE FROM worksheet WHERE id = $ws_id";
    $sql_wd = "DELETE FROM worksheet_detail WHERE id = $wd_id";


    $conn = getConnProduction();

    // Disable FK Check
    $sql_fk_check = "SET FOREIGN_KEY_CHECKS = 0";
    $conn->query($sql_fk_check);

    echo "<br><br><Br><Br><BR>";
    echo $delete_wd = $conn->query($sql_wd);
    echo $delete_ws = $conn->query($sql_ws);


    if ($delete_wd === TRUE && $delete_ws === TRUE) {

        logGeneric($_SESSION['user_id'], 423, "DELETE WORKSHEET; worksheetId={$worksheet_id}");

        $msg = "<div class='w3-bar w3-padding w3-green w3-card-2' style='margin-top: 64px;'>
                    <span class='w3-small'><b>OK:</b><br> Delete success!</span>
                </div>
            ";

        // Reenable FK Check
        $sql_fk_check = "SET FOREIGN_KEY_CHECKS = 1";
        $conn->query($sql_fk_check);

        // JavaScript code to close the current window after 3 seconds
        $javascriptCode = '
        <script>
            setTimeout(function() {
                window.close();
            }, 2000);
        </script>
        ';

        // Output the JavaScript code
        echo $javascriptCode;



    } else {
        echo "Error adding worksheet: " . $conn->error;

        // Reenable FK Check
        $sql_fk_check = "SET FOREIGN_KEY_CHECKS = 1";
        $conn->query($sql_fk_check);
    }
}

?>

<html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
</head>

<body>
<div class="w3-top w3-bar w3-blue-gray">
    <span class="w3-bar-item"><?php echo $title; ?></span>
</div>

<?php echo $msg; ?>

<div class="w3-container w3-center" style="width: 100%; margin-top: 64px">
    <h5>Window will close automatically</h5>
</div>


</body>


</html>