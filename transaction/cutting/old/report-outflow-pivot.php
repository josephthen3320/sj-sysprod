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
        <th class="w3-center"></th>
        <th class="w3-center">KAOS & JAKET</th>
        <th class="w3-center">LADIES WEAR</th>
        <th class="w3-center">MENS WEAR</th>
        <th class="w3-center">Grand Total</th>
    </tr>

    <tbody>
    <?php
    $connTran = getConnTransaction();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

    $sql = "SELECT a.category_id, c.qty_in, c.cmt_id
            FROM suburjaya_transaction.embro AS c
            JOIN suburjaya_production.worksheet_detail AS ws ON c.worksheet_id = ws.worksheet_id
            JOIN suburjaya_production.article AS a ON ws.article_id = a.article_id
            GROUP BY ws.article_id, c.cmt_id";

    $conn1 = getConnProduction();
    $conn2 = getConnTransaction();

    $result2 = $conn1->query($sql);

    $qtyA = $qtyB = $qtyC = 0;

    while ($row = $result2->fetch_assoc()) {

        switch($row['category_id']) {
            case 'A':
                $qtyA += $row['qty_in'];
                break;
            case 'Z':
                $qtyC += $row['qty_in'];
                break;
            default:
                break;
        }
    }

    $grandtotalqty = $qtyA+$qtyB+$qtyC;

    echo "<tr>";
    echo "<td>Embro</td>";
    echo "<td class='w3-right-align'>{$qtyA}</td>";
    echo "<td class='w3-right-align'>{$qtyB}</td>";
    echo "<td class='w3-right-align'>{$qtyC}</td>";
    echo "<td class='w3-right-align'>{$grandtotalqty}</td>";
    echo "<tr>";


    while ($row = $result2->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['cmt_id']}</td>";
        echo "<td class='w3-right-align'></td>";
        echo "<td class='w3-right-align'></td>";
        echo "<td class='w3-right-align'></td>";
        echo "<td class='w3-right-align'></td>";
        echo "<tr>";
    }

    ?>

    <tr style="font-weight: bold">
        <td class='w3-right-align'>Grand Total: </td>
        <td class='w3-right-align'><?= $qtyA ?></td>
        <td class='w3-right-align'><?= $qtyB ?></td>
        <td class='w3-right-align'><?= $qtyC ?></td>
        <td class='w3-right-align'><?= $grandtotalqty ?></td>
    </tr>
    </tbody>
</table>

</body>
</html>
