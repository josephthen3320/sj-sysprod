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
        <th>Perbaikan No.</th>
        <th>Worksheet No.</th>
        <th>Article ID</th>
        <th>Model</th>
        <th>Location</th>
        <th>Qty in</th>
        <th>Qty out</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th colspan="2">Actions</th>
    </tr>
    </thead>
    <tbody>
        <?php
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet_position.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_transaction.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_articles.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_surat_jalan.php";

            $ct_data = fetchAllTransactionByProcess('perbaikan');
            $i = 0;

            if ($ct_data->num_rows == 0) {
                echo "<tr>";
                echo "<td colspan='12'>No data found!</td>";
                echo "</tr>";
            }

            while ($ct = $ct_data->fetch_assoc()) {

                $worksheet = fetchWorksheet($ct['worksheet_id'])->fetch_assoc();
                $article_id = $worksheet['article_id'];
                $article = getArticleById($article_id);

                ++$i;
                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td>{$ct['perbaikan_id']}</td>";
                echo "<td>{$ct['worksheet_id']}</td>";
                echo "<td>{$article}</td>";
                echo "<td>{$article['model_name']}</td>";

                echo "<td>". getCMTNameById($ct['cmt_id']) . "</td>";

                echo "<td>{$ct['qty_in']}</td>";
                echo "<td>";

                if ($ct['qty_out'] <= 0) {
                    $urlParam = "p=perbaikan";
                    $urlParam .= "&i=" . $ct['perbaikan_id'];
                    $urlParam .= "&w=" . $ct['worksheet_id'];
                    echo "<button onclick='openPopupURL2(\"set-qty-out.php?{$urlParam}\", \"qtyOut\", 500, 300)' class='w3-button w3-blue-grey'>" . "<i class='fas fa-plus'></i>" . "</button>";
                } else {
                    echo $ct['qty_out'];
                }

                echo "</td>";

                echo "<td>{$ct['date_in']}</td>";
                echo "<td>{$ct['date_out']}</td>";

                echo "<td>";
                    // Surat Terima
                    $urlSuratTerima = "/transaction/surat-jalan/?i={$ct['st_id']}&t={$ct['perbaikan_id']}&w={$ct['worksheet_id']}";
                    if (checkSuratJalanExistsByTransactionId($ct['perbaikan_id'])) {
                        echo "<button class='w3-button w3-green' onclick='openPopupURL2(\"$urlSuratTerima\", \"suratJalan\", 800, 500)'><i class='fas fa-print'></i></button>";
                    }
                echo "</td>";

                echo "<td>";
                if (getWorksheetPosition($ct['worksheet_id']) == 7) {
                    if ($ct['qty_out'] > 0 && in_array($role, [0,1,2,3,7])) {
                        echo "<button class='w3-button w3-red' onclick='openPopupURL2(\"sendDialog.php?w={$ct['worksheet_id']}&i={$ct['id']}&pi={$ct['perbaikan_id']}&q={$ct['qty_out']}\", \"sendto\", 500, 400)'><i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
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