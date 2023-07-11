<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db_transaction.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

// Construct the SQL query to calculate the sum of qty_out for each cmt_id
$sql = "SELECT cmt_id, worksheet_id, qty_out, SUM(qty_out) AS total_qty FROM cutting GROUP BY cmt_id ORDER BY cmt_id ASC";
$result1 = $conn->query($sql);



?>

<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>

<body>
<table class="w3-table w3-table-all">
    <tr class="">
        <th class="w3-center">CMT</th>
        <th class="w3-center">Total Qty</th>
    </tr>

    <tbody>
    <?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db_combined.php";

    $grand_sum_qty = 0;
    while ($row = $result1->fetch_assoc()) {
        $grand_sum_qty += $row['qty_out'];
        echo "<tr class='w3-pale-yellow'>";

        $cmtId = $row['cmt_id'];
        $cmtname = getCMTNameById($row['cmt_id']);

        echo "<td style='font-weight: bold;'>{$cmtname}</td>";
        echo "<td class='w3-right-align' style='font-weight: bold;'>{$row['total_qty']}</td>";

        echo "</tr>";

        // $sql = "SELECT worksheet_id, qty_out FROM cutting WHERE cmt_id = '$cmtId'";

        $sql = "SELECT ws.article_id, SUM(c.qty_out) AS total_qty_out, c.cmt_id
                FROM suburjaya_transaction.cutting AS c
                JOIN suburjaya_production.worksheet_detail AS ws ON c.worksheet_id = ws.worksheet_id
                GROUP BY ws.article_id, c.cmt_id";

        $conn1 = getConnProduction();
        $conn2 = getConnTransaction();
        $result2 = $conn->query($sql);
        while ($row2 = $result2->fetch_assoc()) {
            if ($row2['cmt_id'] == $cmtId) {

                $at = getArticleById($row2['article_id'])['model_name'];

                echo "<tr>";
                echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;{$at}</td>";
                echo "<td class='w3-right-align'>{$row2['total_qty_out']}</td>";
                echo "</tr>";
            }
        }

    }
    ?>
    <tr class="w3-pale-red" style='font-weight: bold'>
        <td class="w3-right-align">Grand Total: </td>
        <td class="w3-right-align"><?= $grand_sum_qty ?></td>
    </tr>
    </tbody>
</table>
</body>
</html>
