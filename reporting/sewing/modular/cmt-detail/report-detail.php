<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

$conn = getConnTransaction();
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global sewing Report</title>
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
        <th>CMT</th>
        <th>TGL MASUK CMT</th>
        <th>WORKSHEET ID</th>
        <th>NO ARTICLE</th>
        <th>MODEL</th>
        <th>CATEGORY</th>
        <th style="text-align: center">QTY IN</th>
        <th style="text-align: center">QTY OUT</th>
        <th style="text-align: center">HILANG</th>
        <th style="text-align: center">SISA</th>
    </tr>

    <?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

    $conn = getConnTransaction();

    // Step 2: Retrieve data from the "sewing" table
    $sql = "SELECT *, SUM(qty_out) AS total_qty_out
                    FROM sewing
                    WHERE qty_out >= 0 
                    GROUP BY cmt_id, worksheet_id
                    ORDER BY date_in DESC, cmt_id, worksheet_id";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $sql = "SELECT *, SUM(qty_out) AS total_qty_out
                    FROM sewing
                    WHERE qty_out >= 0 
                    GROUP BY cmt_id, worksheet_id
                    ORDER BY cmt_id, worksheet_id";
    }

    $result = $conn->query($sql);

    // Step 3: Generate HTML Table
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $cmt_id = $row["cmt_id"];
        $worksheet_id = $row["worksheet_id"];

        $date_in = $row['date_in'];

        $qty_in = $row['qty_in'];
        $qty_out = $row["qty_out"];
        $qty_missing = $row['qty_missing'];

        $qty_sisa = $qty_in - ($qty_out + $row['qty_missing']);

        // Check if the cmt_id is already in the $data array
        if (!isset($data[$cmt_id])) {
            $data[$cmt_id] = array();
        }

        // Add the worksheet_id and qty_out to the respective cmt_id group
        $data[$cmt_id][] = array("date_in" => $date_in,"worksheet_id" => $worksheet_id, "qty_out" => $qty_out, "qty_in" => $qty_in, "qty_missing" => $qty_missing, "qty_sisa" => $qty_sisa);
    }

    // Loop through the $data array to display the table rows
    foreach ($data as $cmt_id => $worksheets) {
        $cmtName = getCMTNameById($cmt_id);
        $totalCMTIn = $totalCMTOut = $totalMissing = $totalSisa = 0;
        foreach ($worksheets as $worksheet) {

            if ($worksheet['qty_sisa'] == 0) {
                continue;
            }

            $article_id = fetchWorksheet($worksheet['worksheet_id'])->fetch_assoc()['article_id'];
            $article = getArticleById($article_id);

            $categoryName = getCategoryNameById($article['category_id']);

            $totalCMTOut += $worksheet['qty_out'];
            $totalCMTIn += $worksheet['qty_in'];

            $totalMissing += $worksheet['qty_missing'];
            $totalSisa += $worksheet['qty_sisa'];

            echo "<tr>";
            echo "<td style='font-weight: bold;'>$cmtName</td>";
            echo "<td>{$worksheet['date_in']}</td>";
            echo "<td>" . $worksheet['worksheet_id'] . "</td>";
            echo "<td>" . $article_id . "</td>";
            echo "<td>" . $article['model_name'] . "</td>";
            echo "<td>" . $categoryName . "</td>";
            echo "<td style='text-align: center'>" . $worksheet['qty_in'] . "</td>";
            echo "<td style='text-align: center'>" . $worksheet["qty_out"] . "</td>";
            echo "<td style='text-align: center'>" . $worksheet['qty_missing'] . "</td>";
            echo "<td style='text-align: center'>" . $worksheet['qty_sisa'] . "</td>";
            echo "</tr>";
        }
        echo "<tr class='w3-pale-red'>";
        echo "<td colspan='6' style='text-align: right; font-weight: bold'>Total</td>";
        echo "<td style='text-align: center; font-weight: bold'>$totalCMTIn</td>";
        echo "<td style='text-align: center; font-weight: bold'>$totalCMTOut</td>";
        echo "<td style='text-align: center; font-weight: bold'>$totalMissing</td>";
        echo "<td style='text-align: center; font-weight: bold'>$totalSisa</td>";
        echo "</tr>";

        echo "<tr class='w3-white'><td colspan='9'></td></tr>";
    }

    ?>


</table>