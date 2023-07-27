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
        <th class="w3-center w3-cell-middle" style="vertical-align: middle;" rowspan="2">No</th>
        <th class="w3-center w3-cell-middle" style="vertical-align: middle;" rowspan="2">QC Final No.</th>
        <th class="w3-center w3-cell-middle" style="vertical-align: middle;" rowspan="2">Worksheet No.</th>
        <th class="w3-center w3-cell-middle" style="vertical-align: middle;" rowspan="2">Article ID</th>
        <th class="w3-center w3-cell-middle" style="vertical-align: middle;" rowspan="2">Model</th>
        <th class="w3-center w3-cell-middle" style="vertical-align: middle;" rowspan="2">Qty in</th>
        <th class="w3-center w3-cell-middle" style="vertical-align: middle;" rowspan="2">Qty out</th>
        <th class="w3-center w3-cell-middle" style="vertical-align: middle;" colspan="3">Qty Lain-Lain</th>
        <th class="w3-center w3-cell-middle" style="vertical-align: middle;" rowspan="2">Start Date</th>
        <th class="w3-center w3-cell-middle" style="vertical-align: middle;" rowspan="2">End Date</th>
        <th class="w3-center w3-cell-middle" style="vertical-align: middle;" rowspan="2" colspan="2">Actions</th>
    </tr>
    <tr>
        <th class="w3-center w3-white">Hilang</th>
        <th class="w3-center w3-white">Cacat</th>
        <th class="w3-center w3-white">Gagal</th>
    </tr>
    </thead>
    <tbody>
        <?php
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet_position.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_transaction.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_articles.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_surat_jalan.php";

            $ct_data = fetchAllTransactionByProcess('qc_final');
            $i = 0;

            if ($ct_data->num_rows == 0) {
                echo "<tr>";
                echo "<td class='w3-center' colspan='13'>No data found!</td>";
                echo "</tr>";
            }

            while ($ct = $ct_data->fetch_assoc()) {

                $worksheet = fetchWorksheet($ct['worksheet_id'])->fetch_assoc();
                $article_id = $worksheet['article_id'];
                $article = getArticleById($article_id);

                ++$i;
                echo "<tr>";
                echo "<td class='w3-center'>{$i}</td>";
                echo "<td class='w3-center'>{$ct['qc_final_id']}</td>";
                echo "<td class='w3-center'>{$ct['worksheet_id']}</td>";

                echo "<td class='w3-center w3-left-align'>{$article_id}</td>";
                echo "<td class='w3-center w3-left-align'>{$article['model_name']}</td>";


                echo "<td class='w3-center'>{$ct['qty_in']}</td>";
                echo "<td class='w3-center'>";

                if ($ct['qty_out'] <= 0) {
                    $urlParam = "p=qc_final";
                    $urlParam .= "&i=" . $ct['qc_final_id'];
                    $urlParam .= "&w=" . $ct['worksheet_id'];
                    echo "<button onclick='openPopupURL2(\"set-qty-out.php?{$urlParam}\", \"qtyOut\", 500, 300)' class='w3-button w3-blue-grey'>" . "<i class='fas fa-plus'></i>" . "</button>";
                } else {
                    echo $ct['qty_out'];
                }

                echo "</td>";

                $qtyMissing = $qtyFail = $qtyDefect = "-";
                if ($ct['qty_missing'] > 0) {
                    $qtyMissing = $ct['qty_missing'];
                    $qtyFail = $ct['qty_fail'];
                    $qtyDefect = $ct['qty_defect'];
                }

                echo "<td class='w3-center'>{$qtyMissing}</td>";
                echo "<td class='w3-center'>{$qtyDefect}</td>";
                echo "<td class='w3-center'>{$qtyFail}</td>";

                echo "<td class='w3-center'>{$ct['date_in']}</td>";
                echo "<td class='w3-center'>{$ct['date_out']}</td>";

                echo "<td class='w3-center'>";
                    // Surat Terima
                    $urlSuratTerima = "/transaction/surat-jalan/?i={$ct['sj_id']}&t={$ct['qc_final_id']}&w={$ct['worksheet_id']}";
                    if (checkSuratJalanExistsByTransactionId($ct['qc_final_id'])) {
                        echo "<button class='w3-button w3-green' onclick='openPopupURL2(\"$urlSuratTerima\", \"suratJalan\", 800, 500)'><i class='fas fa-print'></i></button>";
                    }
                echo "</td>";

                echo "<td class='w3-center'>";
                if (getWorksheetPosition($ct['worksheet_id']) == 9) {
                    if ($ct['qty_out'] > 0 && in_array($role, [0,1,2,3])) {
                        echo "<button class='w3-button w3-red' onclick='openPopupURL2(\"sendDialog.php?w={$ct['worksheet_id']}&i={$ct['id']}&pi={$ct['qc_final_id']}&q={$ct['qty_out']}\", \"sendto\", 500, 400)'><i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
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

<script>
    function dropdownButton(dropdownId) {
        var x = document.getElementById(dropdownId);

        if (x.className.indexOf('w3-show') === -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }
</script>