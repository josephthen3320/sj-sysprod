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
        <th>Pola Marker No.</th>
        <th>Worksheet No.</th>
        <th>Article No.</th>
        <th>Model</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Surat Jalan</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
        <?php
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet_position.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_transaction.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_surat_jalan.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_articles.php";

            $ct_data = fetchAllTransactionByProcess('pola_marker');
            $i = 0;

            while ($ct = $ct_data->fetch_assoc()) {
                ++$i;

                $worksheet = fetchWorksheet($ct['worksheet_id'])->fetch_assoc();
                $article_id = $worksheet['article_id'];

                $article = getArticleById($article_id);


                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td>{$ct['pola_marker_id']}</td>";
                echo "<td>{$ct['worksheet_id']}</td>";
                echo "<td>{$article_id}</td>";
                echo "<td>{$article['model_name']}</td>";


                echo "<td>{$ct['date_in']}</td>";
                echo "<td>{$ct['date_out']}</td>";

                echo "<td>";
                if (checkSuratJalanExistsByTransactionId($ct['pola_marker_id'])) {
                    $urlSuratJalan = "/transaction/surat-jalan/?i={$ct['sj_id']}&t={$ct['pola_marker_id']}&w={$ct['worksheet_id']}";
                    echo "<button class='w3-button w3-green' onclick='openPopupURL2(\"$urlSuratJalan\")'><i class='fas fa-print'></i></button>";
                }

                echo "</td>";


                echo "<td>";
                if (in_array($role, [0,1,2,3,7]))
                if (getWorksheetPosition($ct['worksheet_id']) <= 1) {
                    echo "<button class='w3-button w3-red' onclick='openPopupURL2(\"sendDialog.php?w={$ct['worksheet_id']}&i={$ct['id']}&pi={$ct['pola_marker_id']}&a={$article_id}\", \"sendtocutting\", 500, 400)'><i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";

                } else {
                    echo "<button class='w3-button w3-hover-red w3-red w3-disabled'><i class=\"fa-solid fa-check\"></i></button>";
                }
                echo "</td>";

                echo "</tr>";

            }


        ?>

    </tbody>
</table>

<!-- Mobile Table -->
<table class="w3-table w3-table-all w3-hide-large w3-hide-medium">
    <?php
    foreach ($ct_data as $index => $ct) {
        $worksheetId = $ct['worksheet_id'];
        $pmId = $ct['pola_marker_id'];
        $details = fetchWorksheetDetails($worksheetId);
        $articleId = $details['article_id'];



        echo "<thead>";
        echo "<tr class='w3-indigo'>";      // todo: change colour later
        echo "<th>Pola Marker ID</th>";
        echo "<th>{$pmId}</th>";
        echo "</tr>";
        echo "</thead>";

        echo "<tbody>";
        echo "<tr>";
        echo "<td>Worksheet ID.</td>";
        echo "<td>{$worksheetId}</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td>Article ID.</td>";
        echo "<td>{$articleId}</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td>Start Date</td>";
        echo "<td>{$ct['date_in']}</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td>End Date</td>";
        echo "<td>{$ct['date_out']}</td>";
        echo "</tr>";

        echo "<tr>";
        // Action buttons
        echo "<td class='w3-center'>";
        if (checkSuratJalanExistsByTransactionId($ct['pola_marker_id'])) {
            $urlSuratJalan = "/transaction/surat-jalan/?i={$ct['sj_id']}&t={$ct['pola_marker_id']}&w={$ct['worksheet_id']}";
            echo "<button style='width: 85%;' class='w3-button w3-green' onclick='openPopupURL2(\"$urlSuratJalan\")'><i class='fas fa-print'></i></button>";
        }
        echo "</td>";
        // Send button
        echo "<td class='w3-center'>";
        if (getWorksheetPosition($worksheetId) == 1) {
            echo "<button style='width: 85%;' class='w3-button w3-red' onclick='openPopupURL2(\"sendDialog.php?w={$ct['worksheet_id']}&i={$ct['id']}&pi={$ct['pola_marker_id']}&a={$article_id}\", \"sendtocutting\", 500, 400)'><i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
        } else {
            echo "<button style='width: 85%;' class='w3-button w3-hover-red w3-red w3-disabled'><i class=\"fa-solid fa-check\"></i></button>";
        }
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td colspan='2'></td>";
        echo "</tr>";
        echo "</tbody>";
    }

    ?>
</table>

</body>
</html>


<script>

</script>