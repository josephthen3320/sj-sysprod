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

<table class="w3-table w3-table-all w3-hide-small w3-small">
    <thead>
    <tr>
        <th>No</th>
        <th>Warehouse No.</th>
        <th>Worksheet No.</th>
        <th>Article ID</th>
        <th>Model</th>
        <th>Date Stored</th>
        <th class="w3-center">Qty</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
        <?php
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet_position.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_transaction.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_articles.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_surat_jalan.php";

            $ct_data = fetchWarehouseData();

            $i = 0;

            if ($ct_data->num_rows == 0) {
                echo "<tr>";
                echo "<td colspan='8'>No data found!</td>";
                echo "</tr>";
            }


            while ($ct = $ct_data->fetch_assoc()) {
                $qty_add_btn = "<button class='w3-button w3-blue-grey w3-round-large' onclick='openPopupURL2(\"add-qty-out.php?i={$ct['id']}&w={$ct['worksheet_id']}&c={$ct['warehouse_id']}\", \"cutout\", 500, 300)'> <i class='fas fa-plus fa-sm'></i></button>";
                ++$i;

                $worksheet = fetchWorksheet($ct['worksheet_id'])->fetch_assoc();
                $article_id = $worksheet['article_id'];

                $article = getArticleById($article_id);

                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td>{$ct['warehouse_id']}</td>";
                echo "<td>{$ct['worksheet_id']}</td>";
                echo "<td>{$article_id}</td>";
                echo "<td>{$article['model_name']}</td>";

                echo "<td>{$ct['date_in']}</td>";
                echo "<td class='w3-center'>{$ct['qty']}</td>";

                echo "<td>SELESAI</td>";



                echo "</tr>";

            }


        ?>

    </tbody>
</table>



<!-- Mobile Table -->
<div class="w3-hide-large w3-hide-medium">
    Mobile view disabled. Please use a computer to view.
</div>


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