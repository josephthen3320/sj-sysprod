<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_classification.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';

$conn = getConnTransaction();

$sql = "SELECT so.*, sew.worksheet_id, sew.cmt_id, sew.qty_out AS qty_out_global
        FROM sewing_out AS so 
        INNER JOIN sewing AS sew ON so.sewing_id = sew.sewing_id 
        WHERE so.qty_out > 0
        ORDER BY so.datestamp DESC;
        ";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $fLocation = $_POST['fLocation'] == "" ? null : $_POST['fLocation'];
    $fStartDate = $_POST['fStartDate'] == "" ? null : $_POST['fStartDate'];
    $fEndDate = $_POST['fEndDate'] == "" ? null : $_POST['fEndDate'];

    // Initialize the base SQL query
    $sql = "SELECT so.*, sew.worksheet_id, sew.cmt_id
            FROM sewing_out AS so 
            INNER JOIN sewing AS sew ON so.sewing_id = sew.sewing_id 
            WHERE so.qty_out > 0";

    // Check if $fLocation has a valid value
    if (isset($fLocation)) {
        // Append the condition for cmt_id if $fLocation is not null
        $sql .= " AND sew.cmt_id = '$fLocation'";
    }

    // Check if $fStartDate and $fEndDate have valid values
    if (isset($fStartDate) && isset($fEndDate)) {
        // Append the condition for date_out if both $fStartDate and $fEndDate are not null
        $sql .= " AND so.datestamp BETWEEN '$fStartDate' AND '$fEndDate'";
    }

    $sql .= " ORDER BY so.datestamp DESC";
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

<!-- todo: enable -->
<button class="w3-button w3-disabled w3-border w3-padding" <?php //onclick="openURL('export-detail.php')" ?>><i class="fas fa-file-lines fa-fw"></i>&nbsp;Export XLSX</button>

<form action method="post">
    <select class="w3-select w3-quarter w3-margin" name="fLocation">
        <option selected value="">Select CMT</option>
        <?php
        $cmtList = fetchAllCMTByType('CT5');

        while ($row = $cmtList->fetch_assoc()) {
            echo "<option value='{$row['cmt_id']}'>{$row['cmt_name']}</option>";
        }
        ?>
    </select>
    <input class="w3-input w3-quarter w3-margin" name="fStartDate" type="date">
    <input class="w3-input w3-quarter w3-margin" name="fEndDate" type="date">

    <button class="w3-button w3-blue w3-margin" type="submit">Filter</button>
</form>

<div class="w3-container w3-padding">
    <div class="w3-half">
        <span class=""><b>CMT: </b><?= getCMTNameById($fLocation) ?? "All" ?></span>
    </div>
    <div class="w3-half">
        <span class=""><b>Periode: </b><?= $fStartDate ?? "All" ?> - <?= $fEndDate ?></span>
    </div>
</div>

<table class="w3-table-all w3-small">
    <tr class="w3-small w3-light-grey">
        <th rowspan="2" style="vertical-align: middle;">DATE</th>
        <th rowspan="2" style="vertical-align: middle;">WORKSHEET ID</th>
        <th rowspan="2" style="vertical-align: middle;">CMT</th>
        <th rowspan="2" style="vertical-align: middle;">ARTICLE ID</th>
        <th rowspan="2" style="vertical-align: middle;">MODEL</th>
        <th rowspan="2" style="vertical-align: middle;">CATEGORY</th>
        <th rowspan="2" style="vertical-align: middle;" class="w3-center">QTY<br>CUTTING</th>

        <th rowspan="2" style="vertical-align: middle;" class="w3-center">QTY<br>OUT</th>
        <th class="w3-center" colspan="2">QTY LAIN-LAIN</th>
        <th rowspan="2" style="vertical-align: middle;" class="w3-center">QTY<br>SISA</th>

    </tr>

    <tr class="w3-light-grey">
        <th class="w3-center" style="vertical-align: middle;">GAGAL<br>SEWING</th>
        <th class="w3-center" style="vertical-align: middle;">HILANG</th>
    </tr>

    <?php
    $totalQty = 0;
    while ($row = $result->fetch_assoc()) {
        $article_id = fetchWorksheet($row['worksheet_id'])->fetch_assoc()['article_id'];
        $article = getArticleById($article_id);
        $cmtName = getCMTNameById($row['cmt_id']);
        $category = getCategoryNameById($article['category_id']);
        $qtyCutting = getCuttingQtyByWorksheetId($row['worksheet_id']);

        $qtySisa = $row['qty_sisa'];

        echo "<tr>";

        echo "<td>" . $row['datestamp'] . "</td>";
        echo "<td>" . $row['worksheet_id'] . "</td>";
        echo "<td>" . $cmtName . "</td>";
        echo "<td>" . $article['article_id'] . "</td>";
        echo "<td>" . $article['model_name'] . "</td>";
        echo "<td>" . $category . "</td>";
        echo "<td class='w3-center'>" . $qtyCutting . "</td>";

        echo "<td class='w3-center'>" . $row['qty_out'] . "</td>";
        echo "<td class='w3-center'>" . $row['qty_fail'] . "</td>";
        echo "<td class='w3-center'>" . $row['qty_missing'] . "</td>";

        echo "<td class='w3-center'>" . $qtySisa . "</td>";

        echo "</tr>";

        $totalQty += $row['qty_out'];

    }
    ?>

    <tr>
        <td colspan="7" class="w3-right-align" style="font-weight: bold">Total: </td>
        <td class="w3-center"><?= $totalQty ?></td>
        <td colspan="3">&nbsp;</td>
    </tr>

</table>


</body>
</html>