<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

$conn = getConnTransaction();

// Step 2: Retrieve data from the "sewing" table
$sql = "SELECT cmt_id, SUM(qty_in) AS total_qty_in, worksheet_id, qty_in
                    FROM sewing
                    GROUP BY cmt_id, worksheet_id
                    ORDER BY cmt_id, worksheet_id";

$result = $conn->query($sql);

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
        <th></th>
        <th style="text-align: center">MENS WEAR</th>
        <th style="text-align: center">LADIES WEAR</th>
        <th style="text-align: center">KAOS DAN JAKET</th>
        <th style="text-align: center">LAIN-LAIN</th>
        <th style="text-align: center; font-weight: bold;">Grand Total</th>
    </tr>

    <?php
    // Step 3: Generate HTML Table
    $connProduction = getConnProduction();

    // Initialize cmtArray with keys A, B, and C for each cmt_id
    $sewingCMTSQL = "SELECT cmt_id FROM cmt WHERE cmt_type = 'CT5'";
    $sewingResult = $connProduction->query($sewingCMTSQL);
    $cmtArray = array();

    while ($sew = $sewingResult->fetch_assoc()) {
        $cmt_id = $sew['cmt_id'];
        $cmtArray[$cmt_id] = array('A' => 0, 'B' => 0, 'C' => 0, 'Lain' => 0);
    }

    // Reset Result
    $sql = "SELECT * FROM sewing";
    $result = $conn->query($sql);

    $totalA = $totalB = $totalC = $totalL = 0;

    while ($ct = $result->fetch_assoc()) {
        $article_id = fetchWorksheet($ct['worksheet_id'])->fetch_assoc()['article_id'];
        $article = getArticleById($article_id);
        $categoryId = $article['category_id'];
        $cmtId = $ct['cmt_id'];

        // Add to the $cmtArray only if the category_id is one of 'A', 'B', or 'C'
        if (in_array($categoryId, ['A', 'B', 'C'])) {
            $cmtArray[$cmtId][$categoryId] += $ct['qty_in'];
        } else {
            $cmtArray[$cmtId]['Lain'] += $ct['qty_in'];
        }

        // Calculate the grand total
        switch ($categoryId) {
            case 'A':
                $totalA += $ct['qty_in'];
                break;
            case 'B':
                $totalB += $ct['qty_in'];
                break;
            case 'C':
                $totalC += $ct['qty_in'];
                break;
            default:
                $totalL += $ct['qty_in'];
                break;
        }
    }

    // Calculate the grand total
    $grandTotal = $totalA + $totalB + $totalC + $totalL;

    // Loop through the $cmtArray and print the data in the table
    foreach ($cmtArray as $cmtId => $categories) {
        $cmtName = getCMTNameById($cmtId);
        $cmtTotal = array_sum($categories);

        // Skip printing if the grand total for the cmt_id is 0
        if ($cmtTotal === 0) {
            continue;
        }

        echo '<tr>';
        echo '<td>' . $cmtName . '</td>';
        echo '<td style="text-align: center;">' . $categories['A'] . '</td>';
        echo '<td style="text-align: center;">' . $categories['B'] . '</td>';
        echo '<td style="text-align: center;">' . $categories['C'] . '</td>';
        echo '<td style="text-align: center;">' . $categories['Lain'] . '</td>';
        echo '<td style="text-align: center; font-weight: bold;">' . $cmtTotal . '</td>';
        echo '</tr>';
    }

    echo '<tr class="w3-pale-red" style="font-weight: bold">';
    echo '<td>Jahit</td>';
    echo '<td style="text-align: center;">' . $totalA . '</td>';
    echo '<td style="text-align: center;">' . $totalB . '</td>';
    echo '<td style="text-align: center;">' . $totalC . '</td>';
    echo '<td style="text-align: center;">' . $totalL . '</td>';
    echo '<td style="text-align: center; font-weight: bold;">' . $grandTotal . '</td>';
    echo '</tr>';

    ?>




</table>