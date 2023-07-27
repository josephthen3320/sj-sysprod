<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

$conn = getConnTransaction();

// Step 2: Retrieve data from the "cutting" table
$sql = "SELECT cmt_id, SUM(qty_out) AS total_qty_out, worksheet_id, qty_out
                    FROM cutting
                    GROUP BY cmt_id, worksheet_id
                    ORDER BY cmt_id, worksheet_id";

$result = $conn->query($sql);

?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global cutting Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>

<style>
    .nav-scrollbar::-webkit-scrollbar {
        width: 2px;
        opacity:0;
        transition: opacity 0.3s;
    }
    .nav-scrollbar::-webkit-scrollbar-thumb {
        background-color: #9c9794;
    }
    .nav-scrollbar:hover::-webkit-scrollbar,
    .nav-scrollbar::-webkit-scrollbar-thumb:active {
        opacity: 1;
    }
</style>
<body class="nav-scrollbar">

<table class="w3-table-all w3-small">
    <tr>
        <th colspan="2">Row Labels</th>
        <th>Sum of QTY</th>
    </tr>

    <?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

    $conn = getConnTransaction();

    // Step 2: Retrieve data from the "cutting" table
    $sql = "SELECT cmt_id, SUM(qty_out) AS total_qty_out, worksheet_id, qty_out
                    FROM cutting
                    GROUP BY cmt_id, worksheet_id
                    ORDER BY cmt_id, worksheet_id";

    $result = $conn->query($sql);

    // Step 3: Generate HTML Table
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $cmt_id = $row["cmt_id"];
        $worksheet_id = $row["worksheet_id"];


        $qty_out = $row["qty_out"];

        // Check if the cmt_id is already in the $data array
        if (!isset($data[$cmt_id])) {
            $data[$cmt_id] = array();
        }

        // Add the worksheet_id and qty_out to the respective cmt_id group
        $data[$cmt_id][] = array("worksheet_id" => $worksheet_id, "qty_out" => $qty_out);
    }

    // Loop through the $data array to display the table rows
    foreach ($data as $cmt_id => $worksheets) {
        $cmtName = getCMTNameById($cmt_id);
        echo "<tr class='w3-pale-red'>";
        echo "<th colspan='2'>" . $cmtName . "</th>";
        echo "<th>" . array_sum(array_column($worksheets, "qty_out")) . "</th>";
        echo "</tr>";

        foreach ($worksheets as $worksheet) {
            $article_id = fetchWorksheet($worksheet['worksheet_id'])->fetch_assoc()['article_id'];
            $article = getArticleById($article_id);


            echo "<tr>";
            echo "<td>" . $worksheet['worksheet_id'] . "</td>";
            echo "<td>" . $article['model_name'] . "</td>";
            echo "<td>" . $worksheet["qty_out"] . "</td>";
            echo "</tr>";
        }
    }

    ?>


</table>