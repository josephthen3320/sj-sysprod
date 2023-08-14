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
        <th>Row Labels</th>
        <th>Sum of QTY</th>
    </tr>

    <?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

    $conn = getConnTransaction();
    $conn2 = getConnProduction();

    //$dbProductioName = "suburjaya_production";     // LOCAL NAME
    $dbProductioName = "subm6595_sj_production";      // ONLINE NAME

    $sql = "
    SELECT cmt_id, a.category_id, SUM(cut.qty_out) AS total_qty_out
    FROM cutting AS cut
    INNER JOIN {$dbProductioName}.worksheet_detail AS wd ON cut.worksheet_id = wd.worksheet_id
    INNER JOIN {$dbProductioName}.article AS a ON wd.article_id = a.article_id
    WHERE cut.date_cut IS NOT NULL
    GROUP BY cmt_id, a.category_id
    ORDER BY cmt_id, a.category_id";

    $result = $conn->query($sql);

    // Initialize an array to store the data
    $data = array();

    // Fetch and store the data in the $data array
    while ($row = $result->fetch_assoc()) {
        $cmt_id = $row["cmt_id"];
        $category_id = $row["category_id"];
        $qty_out = $row["total_qty_out"];

        // Check if the cmt_id is already in the $data array
        if (!isset($data[$cmt_id])) {
            $data[$cmt_id] = array();
        }

        // Add the category_id and qty_out to the respective cmt_id group
        $data[$cmt_id][] = array("category_id" => $category_id, "qty_out" => $qty_out);
    }

    // Loop through the $data array to display the table rows
    foreach ($data as $cmt_id => $categories) {
        $cmtName = getCMTNameById($cmt_id);
        echo "<tr class='w3-pale-red'>";
        echo "<th colspan='2'>" . $cmtName . "</th>";
        echo "</tr>";

        foreach ($categories as $category) {
            echo "<tr>";
            echo "<td>" . getCategoryNameById($category['category_id']) . "</td>";
            echo "<td>" . $category["qty_out"] . "</td>";
            echo "</tr>";
        }

        // Display the total qty_out for the current cmt_id
        echo "<tr>";
        echo "<td class='w3-right-align' style='font-weight: bold'>TOTAL: </td>";
        echo "<td>" . array_sum(array_column($categories, "qty_out")) . "</td>";
        echo "</tr>";
    }


    ?>


</table>