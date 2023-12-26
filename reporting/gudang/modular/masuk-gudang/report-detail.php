<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_classification.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';

$conn = getConnTransaction();

$sql = "SELECT *
        FROM warehouse 
        ORDER BY date_in DESC, id DESC;
        ";

$fStartDate = $fEndDate = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $fStartDate = $_POST['fStartDate'] == "" ? null : $_POST['fStartDate'];
    $fEndDate = $_POST['fEndDate'] == "" ? null : $_POST['fEndDate'];

    // Initialize the base SQL query
    $sql = "SELECT *
            FROM warehouse";

    // Check if $fStartDate and $fEndDate have valid values
    if (isset($fStartDate) && isset($fEndDate)) {
        // Append the condition for date_out if both $fStartDate and $fEndDate are not null
        $sql .= " WHERE date_in BETWEEN '$fStartDate' AND '$fEndDate'";
    }

    $sql .= " ORDER BY date_in DESC, id DESC";
}

$result = $conn->query($sql);

?>


<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cutting Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
    <script src="/assets/js/utils.js"></script>

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

<form action method="post">
    <input class="w3-input w3-quarter w3-margin" name="fStartDate" type="date">
    <input class="w3-input w3-quarter w3-margin" name="fEndDate" type="date" value="<?= date('Y-m-d'); ?>">

    <button class="w3-button w3-blue w3-margin" type="submit">Filter</button>
</form>
<form action="export-detail.php" method="post">
    <input hidden value="<?= $sql ?>" name="sql">
    <input hidden value="<?= $_POST['fStartDate'] ?? "" ?>" name="fStartDate">
    <input hidden value="<?= $_POST['fEndDate'] ?? "" ?>" name="fEndDate">
    <button type="submit" class="w3-button w3-border w3-padding"><i class="fas fa-file-lines fa-fw"></i>&nbsp;Export XLSX</button>
</form>

<div class="w3-container w3-padding">
    <div class="w3-half">
        <span class=""><b>Periode: </b><?= $fStartDate ?? "All" ?> - <?= $fEndDate ?></span>
    </div>
</div>

<table class="w3-table-all w3-small">
    <tr class="w3-small w3-light-grey">
        <th style="vertical-align: middle;">DATE</th>
        <th style="vertical-align: middle;">WORKSHEET ID</th>
        <th style="vertical-align: middle;">ARTICLE ID</th>
        <th style="vertical-align: middle;">MODEL</th>
        <th style="vertical-align: middle;">CATEGORY</th>
        <th style="vertical-align: middle;" class="w3-center">QTY<br>CUTTING</th>
        <th style="vertical-align: middle;" class="w3-center">QTY<br>MASUK GUDANG</th>

    </tr>

    <?php
    $totalQty = 0;
    while ($row = $result->fetch_assoc()) {
        $article_id = fetchWorksheet($row['worksheet_id'])->fetch_assoc()['article_id'];
        $article = getArticleById($article_id);
        $category = getCategoryNameById($article['category_id']);
        $qtyCutting = getCuttingQtyByWorksheetId($row['worksheet_id']);

        echo "<tr>";

        echo "<td>" . $row['date_in'] . "</td>";
        echo "<td>" . $row['worksheet_id'] . "</td>";
        echo "<td>" . $article['article_id'] . "</td>";
        echo "<td>" . $article['model_name'] . "</td>";
        echo "<td>" . $category . "</td>";
        echo "<td class='w3-center'>" . $qtyCutting . "</td>";

        echo "<td class='w3-center'>" . $row['qty'] . "</td>";

        echo "</tr>";

        $totalQty += $row['qty'];

    }
    ?>

    <tr>
        <td colspan="6" class="w3-right-align" style="font-weight: bold">Total: </td>
        <td class="w3-center"><?= $totalQty ?></td>
    </tr>

</table>


</body>
</html>