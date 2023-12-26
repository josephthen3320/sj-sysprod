<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_classification.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';

$conn = getConnTransaction();

$fLocation = $fStartDate = $fEndDate = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $fLocation = $_POST['fLocation'] == "" ? null : $_POST['fLocation'];
    $fStartDate = $_POST['fStartDate'] == "" ? null : $_POST['fStartDate'];
    $fEndDate = $_POST['fEndDate'] == "" ? null : $_POST['fEndDate'];

    // Initialize the base SQL query
    $sql = "SELECT sew.cmt_id, SUM(so.qty_out) AS qty_out_cmt, SUM(so.qty_sisa) AS qty_sisa_cmt, SUM(so.qty_missing) AS qty_missing_cmt
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

    $sql .= " GROUP BY sew.cmt_id ORDER BY qty_out_cmt DESC";
} else {
    // Default SQL query when no POST data is received
    $sql = "SELECT sew.cmt_id, SUM(so.qty_out) AS qty_out_cmt, SUM(so.qty_sisa) AS qty_sisa_cmt, SUM(so.qty_missing) AS qty_missing_cmt
            FROM sewing_out AS so 
            INNER JOIN sewing AS sew ON so.sewing_id = sew.sewing_id 
            WHERE so.qty_out > 0
            GROUP BY sew.cmt_id
            ORDER BY qty_out_cmt DESC";
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
    <select class="w3-select w3-quarter w3-margin" name="fLocation">
        <option selected value="">Select Location</option>
        <?php
        $cmtList = fetchAllCMTByType('CT5');

        while ($row = $cmtList->fetch_assoc()) {
            echo "<option value='{$row['cmt_id']}'>{$row['cmt_name']}</option>";
        }
        ?>
    </select>
    <input class="w3-input w3-quarter w3-margin" name="fStartDate" type="date">
    <input class="w3-input w3-quarter w3-margin" name="fEndDate" type="date" value="<?= date('Y-m-d'); ?>">

    <button class="w3-button w3-blue w3-margin" type="submit">Filter</button>
</form>
<form action="export-detail.php" method="post">
    <input hidden value="<?= $sql ?>" name="sql">
    <input hidden value="<?= $_POST['fStartDate'] ?? "" ?>" name="fStartDate">
    <input hidden value="<?= $_POST['fEndDate'] ?? "" ?>" name="fEndDate">
    <input hidden value="<?= $_POST['fLocation'] ?? "" ?>" name="fLocation">
    <button type="submit" class="w3-button w3-border w3-padding"><i class="fas fa-file-lines fa-fw"></i>&nbsp;Export XLSX</button>
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
        <th style="vertical-align: middle;">CMT</th>
        <th style="vertical-align: middle;" class="w3-center">QTY OUT</th>
        <th style="vertical-align: middle;" class="w3-center">QTY HILANG</th>
        <th style="vertical-align: middle;" class="w3-center">QTY SISA</th>
    </tr>

    <?php
    $totalQty = $totalMissing = $totalSisa = 0;
    while ($row = $result->fetch_assoc()) {
        $cmtName = getCMTNameById($row['cmt_id']);
        $category = getCategoryNameById($article['category_id']);

        $qtySisa = $row['qty_sisa_cmt'];

        echo "<tr>";

        echo "<td>" . $cmtName . "</td>";

        echo "<td class='w3-center'>" . $row['qty_out_cmt'] . "</td>";
        echo "<td class='w3-center'>" . $row['qty_missing_cmt'] . "</td>";
        echo "<td class='w3-center'>" . $qtySisa . "</td>";

        echo "</tr>";

        $totalQty += $row['qty_out_cmt'];
        $totalMissing += $row['qty_missing_cmt'];
        $totalSisa += $qtySisa;

    }
    ?>

    <tr>
        <td class="w3-right-align" style="font-weight: bold">Total: </td>
        <td class="w3-center"><?= $totalQty ?></td>
        <td class="w3-center"><?= $totalMissing ?></td>
        <td class="w3-center"><?= $totalSisa ?></td>
    </tr>

</table>


</body>
</html>