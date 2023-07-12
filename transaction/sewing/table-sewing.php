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

<style>
    td {
        text-align: center !important;
    }
</style>

<table class="w3-table w3-table-all">
    <thead class="">
    <tr class="w3-light-grey">
        <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
        <th rowspan="2" style="text-align: center; vertical-align: middle;">Sewing No.</th>
        <th rowspan="2" style="text-align: center; vertical-align: middle;">Worksheet No.</th>
        <th rowspan="2" style="text-align: center; vertical-align: middle;">Article No.</th>
        <th rowspan="2" style="text-align: center; vertical-align: middle;">Qty In</th>
        <th rowspan="2" style="text-align: center; vertical-align: middle;">Qty Out</th>
        <th colspan="3" style="text-align: center; vertical-align: middle;">Qty Lain-Lain</th>
        <th rowspan="2" style="text-align: center; vertical-align: middle;">Start Date</th>
        <th rowspan="2" style="text-align: center; vertical-align: middle;">End Date</th>
        <th rowspan="2" style="text-align: center; vertical-align: middle;">Actions</th>
        <th rowspan="2" style="text-align: center; vertical-align: middle;">Send to</th>
    </tr>
    <tr>
        <th style="text-align: center; vertical-align: middle;">Gagal</th>
        <th style="text-align: center; vertical-align: middle;">Cacat</th>
        <th style="text-align: center; vertical-align: middle;">Hilang</th>
    </tr>
    </thead>
    <tbody>

        <?php
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet_position.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_transaction.php";
        include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_classification.php";
        include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet.php";
        include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_surat_jalan.php";

            $ct_data = fetchAllTransactionByProcess('sewing');
            $i = 0;

            if ($ct_data->num_rows == 0) {
                echo "<tr>";
                echo "<td colspan='8'>No data found!</td>";
                echo "</tr>";
            }


            while ($ct = $ct_data->fetch_assoc()) {

                $worksheet = fetchWorksheet($ct['worksheet_id'])->fetch_assoc();
                $article_id = $worksheet['article_id'];

                ++$i;
                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td>{$ct['sewing_id']}</td>";
                echo "<td>{$ct['worksheet_id']}</td>";
                echo "<td>{$article_id}</td>";

                echo "<td>{$ct['qty_in']}</td>";
                echo "<td>";
                if ($ct['qty_out'] <= 0) {
                    $urlParam = "p=sewing";
                    $urlParam .= "&i=" . $ct['sewing_id'];
                    $urlParam .= "&w=" . $ct['worksheet_id'];
                    $urlParam .= "&q=" . $ct['qty_in'];
                    echo "<button onclick='openPopupURL2(\"set-qty-out.php?{$urlParam}\", \"qtyOut\", 500, 300)' class='w3-button w3-blue-grey'>" . "<i class='fas fa-plus'></i>" . "</button>";
                } else {
                    echo $ct['qty_out'];
                }
                echo "</td>";

                echo "<td>";
                if ($ct['qty_out'] > 0) {
                    echo $ct['qty_fail'];
                }
                echo "</td>";

                echo "<td>";
                if ($ct['qty_out'] > 0) {
                    echo $ct['qty_defect'];
                }
                echo "</td>";

                echo "<td>";
                if ($ct['qty_out'] > 0) {
                    echo $ct['qty_missing'];
                }
                echo "</td>";


                echo "<td>{$ct['date_in']}</td>";
                echo "<td>{$ct['date_out']}</td>";

                echo "<td>";
                $urlSuratTerima = "/transaction/surat-jalan/?i={$ct['sj_id']}&t={$ct['sewing_id']}&w={$ct['worksheet_id']}";
                if (checkSuratJalanExistsByTransactionId($ct['sewing_id'])) {
                    echo "<button class='w3-button w3-green' onclick='openPopupURL2(\"$urlSuratTerima\", \"suratJalan\", 800, 500)'><i class='fas fa-print'></i></button>";
                }
                echo "</td>";


                echo "<td>";
                if (getWorksheetPosition($ct['worksheet_id']) == 6) {
                    if ($ct['qty_out'] > 0) {
                        echo "<button class='w3-button w3-red' onclick='openPopupURL2(\"sendDialog.php?w={$ct['worksheet_id']}&i={$ct['id']}&pi={$ct['sewing_id']}&q={$ct['qty_in']}\", \"sendto\", 500, 400)'><i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
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