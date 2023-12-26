<?php
    session_start();
    $role = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>
<script src="/assets/js/utils.js"></script>
<body>

<table class="w3-table w3-table-all w3-small">
    <thead>
    <tr>
        <th>No</th>
        <th>Print/Sablon No.</th>
        <th>Worksheet No.</th>
        <th>Article ID</th>
        <th>Model</th>
        <th>Qty In</th>
        <th>Qty Out</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Actions</th>
        <th>Send to</th>
    </tr>
    </thead>
    <tbody>
        <?php
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet_position.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_transaction.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_classification.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_surat_jalan.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_articles.php";

            $ct_data = fetchAllTransactionByProcess('print_sablon');
            $i = 0;

            if ($ct_data->num_rows == 0) {
                echo "<tr>";
                echo "<td class='w3-center' colspan='11'>No data found!</td>";
                echo "</tr>";
            }


            while ($ct = $ct_data->fetch_assoc()) {

                $worksheet = fetchWorksheet($ct['worksheet_id'])->fetch_assoc();
                $article_id = $worksheet['article_id'];
                $article = getArticleById($article_id);

                ++$i;
                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td>{$ct['print_sablon_id']}</td>";
                echo "<td>{$ct['worksheet_id']}</td>";

                echo "<td>{$article_id}</td>";
                echo "<td>{$article['model_name']}</td>";

                echo "<td>{$ct['qty_in']}</td>";
                echo "<td>";
                if ($ct['qty_out'] <= 0 && in_array($role, [0,1,2,3,4,7])) {
                    $urlParam = "p=print_sablon";
                    $urlParam .= "&i=" . $ct['print_sablon_id'];
                    $urlParam .= "&w=" . $ct['worksheet_id'];
                    echo "<button onclick='openPopupURL2(\"set-qty-out.php?{$urlParam}\", \"qtyOut\", 500, 300)' class='w3-button w3-blue-grey'>" . "<i class='fas fa-plus'></i>" . "</button>";
                } else {
                    echo $ct['qty_out'];
                }
                echo "</td>";


                echo "<td>{$ct['date_in']}</td>";
                echo "<td>{$ct['date_out']}</td>";

                echo "<td>";
                $urlSuratTerima = "/transaction/surat-jalan/?i={$ct['sj_id']}&t={$ct['print_sablon_id']}&w={$ct['worksheet_id']}";
                if (checkSuratJalanExistsByTransactionId($ct['print_sablon_id'])) {
                    echo "<button class='w3-button w3-green' onclick='openPopupURL2(\"$urlSuratTerima\", \"suratJalan\", 800, 500)'><i class='fas fa-print'></i></button>";
                }
                echo "</td>";


                echo "<td>";
                if (getWorksheetPosition($ct['worksheet_id']) == 4) {
                    if ($ct['qty_out'] > 0 && in_array($role, [0,1,2,3,7])) {
                        echo "<button class='w3-button w3-red' onclick='openPopupURL2(\"sendDialog.php?w={$ct['worksheet_id']}&i={$ct['id']}&pi={$ct['print_sablon_id']}&q={$ct['qty_in']}\", \"sendto\", 500, 400)'><i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
                    }

                } else {
                    echo "<button class='w3-button w3-hover-red w3-red w3-disabled'><i class='fas fa-check'></i></button>";
                }
                echo "</td>";

                echo "</tr>";

            }


        ?>

    </tbody>
</table>

</body>
</html>