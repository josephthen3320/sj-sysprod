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

<table class="w3-table w3-table-all">
    <thead>
    <tr>
        <th>No</th>
        <th>Cutting No.</th>
        <th>Worksheet No.</th>
        <th>Location</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th class="w3-center">Qty Cutting</th>
        <th>SJ</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
        <?php
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet_position.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_transaction.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_classification.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_surat_jalan.php";


        $ct_data = fetchAllTransactionByProcess('cutting');
            $i = 0;

            if ($ct_data->num_rows == 0) {
                echo "<tr>";
                echo "<td colspan='9'>No data found!</td>";
                echo "</tr>";
            }


            while ($ct = $ct_data->fetch_assoc()) {
                $qty_add_btn = "<button class='w3-button w3-blue-grey w3-round-large' onclick='openPopupURL(\"add-qty-out.php?i={$ct['id']}&w={$ct['worksheet_id']}&c={$ct['cutting_id']}\", \"cutout\")'>Qty out &nbsp;<i class='fas fa-plus fa-sm'></i></button>";
                ++$i;

                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td>{$ct['cutting_id']}</td>";
                echo "<td>{$ct['worksheet_id']}</td>";

                echo "<td>". getCMTNameById($ct['cmt_id']) . "</td>";

                echo "<td>{$ct['date_in']}</td>";
                echo "<td>{$ct['date_out']}</td>";

                echo "<td class='w3-center'>" . $ct['qty_out'] . "</td>";


                echo "<td>";
                if (checkSuratJalanExistsByTransactionId($ct['cutting_id'])) {
                    $urlSuratJalan = "/transaction/surat-jalan/?i={$ct['sj_id']}&t={$ct['cutting_id']}&w={$ct['worksheet_id']}";
                    echo "<button class='w3-button w3-green' onclick='openPopupURL2(\"$urlSuratJalan\")'>Print SJ</button>";
                }

                if ($ct['qty_out'] <= 0) {
                    echo "<td>" . $qty_add_btn . "</td>";
                }


                echo "<td>";

                if (getPositionStatus($ct['worksheet_id'], 'cutting') == 0) {
                    if($ct['qty_out'] > 0) {
                        echo "<button class='w3-button w3-red' onclick='openPopupURL(\"sendDialog.php?w={$ct['worksheet_id']}&i={$ct['id']}&pi={$ct['cutting_id']}&q={$ct['qty_out']}\", \"sendtocutting\", 500, 400)'>Send to &nbsp;&nbsp;<i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
                    }

                } else {
                    echo "<button class='w3-button w3-hover-red w3-red w3-disabled'>Sent &nbsp;&nbsp;<i class=\"fa-solid fa-check\"></i></button>";
                }
                echo "</td>";

                echo "</tr>";

            }


        ?>

    </tbody>
</table>

</body>
</html>