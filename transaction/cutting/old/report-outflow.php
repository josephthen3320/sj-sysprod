<?php

// Default start and end dates
$startDate = null;
$endDate = null;
$cmtFilter = null;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the selected start and end dates from the form
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $cmtFilter = $_POST['cmt_filter'];
}

?>

<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">

    <style>
        .text-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
<form method="POST" action="">
    <div class="w3-row">

        <style>
            .form-container {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-bottom: 10px;
            }

            .form-container .w3-input,
            .form-container .w3-select {
                width: 150px;
            }

            .form-container .w3-button {
                padding: 8px 16px;
                background-color: #4CAF50;
                color: #fff;
                border: none;
                cursor: pointer;
            }

            .form-container .w3-button:hover {
                background-color: #45a049;
            }
        </style>

        <div class="form-container">
            <input class="w3-border w3-input" type="date" id="start_date" name="start_date" value="<?php echo $startDate; ?>">
            <span>to</span>
            <input class="w3-border w3-input" type="date" id="end_date" name="end_date" value="<?php echo $endDate ?? date('Y-m-d'); ?>">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="cmt_filter">CMT:</label>
            <select class="w3-border w3-select" id="cmt_filter" name="cmt_filter">
                <option value="">All</option>
                <?php
                include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db_combined.php';
                $connProd = getConnProduction();
                $cmtQuery = "SELECT cmt_id, cmt_name FROM cmt WHERE cmt_type = 'CT2'";
                $cmtResult = $connProd->query($cmtQuery);
                while ($cmtRow = $cmtResult->fetch_assoc()) {
                    $cmtId = $cmtRow['cmt_id'];
                    $cmtName = $cmtRow['cmt_name'];
                    $selected = ($cmtFilter === $cmtId) ? 'selected' : '';
                    echo "<option value=\"$cmtId\" $selected>$cmtName</option>";
                }
                ?>
            </select>
            <button class="w3-button" type="submit">Filter</button>
        </div>

    </div>
</form>

<table class="w3-table w3-table-all">
    <tr>
        <th>Tanggal</th>
        <th>No SPK</th>
        <th style='width:50px' class="w3-right-align">Qty</th>
        <th>Proses</th>
        <th>CMT</th>
        <th>No Artikel</th>
        <th>Model</th>
        <th>Category</th>
    </tr>

    <tbody>
    <?php
    $connTran = getConnTransaction();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

    // Construct the SQL query based on the selected filters
    $sql = "SELECT * FROM embro";

    $whereClause = array();

    if ($startDate && $endDate) {
        $whereClause[] = "date_in BETWEEN '$startDate' AND '$endDate'";
    }

    if ($cmtFilter) {
        $whereClause[] = "cmt_id = '$cmtFilter'";
    }

    if (!empty($whereClause)) {
        $sql .= " WHERE " . implode(" AND ", $whereClause);
    }

    $sql .= " ORDER BY date_in ASC";

    $result = $connTran->query($sql);

    $sum_qty = 0;

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";

        $worksheet = fetchWorksheetDetails($row['worksheet_id']);
        $cmtname = getCMTNameById($row['cmt_id']);
        $article = getArticleById($worksheet['article_id']);
        $modelname = $article['model_name'];
        $categoryName = getCategoryNameById($article['category_id']);

        echo "<td>{$row['date_in']}</td>";
        echo "<td>{$row['worksheet_id']}</td>";
        echo "<td class='w3-right-align'>{$row['qty_in']}</td>";
        echo "<td>Embro</td>";
        echo "<td>{$cmtname}</td>";
        echo "<td>{$worksheet['article_id']}</td>";
        echo "<td>{$modelname}</td>";
        echo "<td>{$categoryName}</td>";

        echo "</tr>";

        $sum_qty += $row['qty_in'];
    }
    ?>

    <tr style="font-weight: bold">
        <td class='w3-right-align' colspan="2">Total: </td>
        <td class='w3-right-align'><?= $sum_qty ?></td>
        <td colspan="5"></td>
    </tr>
    </tbody>
</table>

</body>
</html>
