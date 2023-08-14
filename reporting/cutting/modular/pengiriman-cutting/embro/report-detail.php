<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_classification.php';

    $conn = getConnTransaction();

    $sql = "SELECT * FROM embro WHERE date_in IS NOT null ORDER BY date_in DESC";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $fLocation = $_POST['fLocation'] == "" ? null : $_POST['fLocation'];
        $fStartDate = $_POST['fStartDate'] == "" ? null : $_POST['fStartDate'];
        $fEndDate = $_POST['fEndDate'] == "" ? null : $_POST['fEndDate'];

        // Initialize the base SQL query
        $sql = "SELECT * FROM embro WHERE 1";

        // Check if $fLocation has a valid value
        if (isset($fLocation)) {
            // Append the condition for cmt_id if $fLocation is not null
            $sql .= " AND cmt_id = '$fLocation'";
        }

        // Check if $fStartDate and $fEndDate have valid values
        if (isset($fStartDate) && isset($fEndDate)) {
            // Append the condition for date_in if both $fStartDate and $fEndDate are not null
            $sql .= " AND date_in BETWEEN '$fStartDate' AND '$fEndDate'";
        }
        $sql .= " ORDER BY date_in DESC";

    }
    $result = $conn->query($sql);

?>


<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embro Report</title>
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

<form action method="post">
    <select class="w3-select w3-quarter w3-margin" name="fLocation">
        <option selected value="">Select Location</option>
        <?php
        $cmtList = fetchAllCMTByType('CT2');

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

<table class="w3-table-all w3-small">
    <tr class="w3-small">
        <th>DATE</th>
        <th>WORKSHEET ID</th>
        <th>QTY</th>
        <th>PROSES</th>
        <th>CMT</th>
        <th>ARTICLE ID</th>
        <th>MODEL</th>
        <th>CATEGORY</th>
    </tr>

    <?php
    $totalQty = 0;
        while ($row = $result->fetch_assoc()) {
            $article_id = fetchWorksheet($row['worksheet_id'])->fetch_assoc()['article_id'];
            $article = getArticleById($article_id);

            $cmtName = getCMTNameById($row['cmt_id']);

            echo "<tr>";

            echo "<td>" . $row['date_in'] . "</td>";
            echo "<td>" . $row['worksheet_id'] . "</td>";
            echo "<td>" . $row['qty_in'] . "</td>";
            echo "<td>" . "EMBRO" . "</td>";
            echo "<td>" . $cmtName . "</td>";
            echo "<td>" . $article['article_id'] . "</td>";
            echo "<td>" . $article['model_name'] . "</td>";

            $categoryName = getCategoryNameById($article['category_id']);
            echo "<td>" . $categoryName . "</td>";

            echo "</tr>";

            $totalQty += $row['qty_in'];

        }
    ?>

    <tr>
        <td colspan="2" class="w3-right-align" style="font-weight: bold">Total: </td>
        <td><?= $totalQty ?></td>
        <td colspan="6">&nbsp;</td>
    </tr>

</table>


</body>
</html>




