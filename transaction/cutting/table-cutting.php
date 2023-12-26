<?php
session_start();
$role = $_SESSION['user_role'];

include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet_position.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_transaction.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_articles.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_surat_jalan.php";

if ($_SESSION['user_role'] == 4) {
    $ct_data = fetchCuttingCipadungTransaction();
} else if ($_SESSION['user_role'] == 7) {
    $ct_data = fetchCuttingKembarTransaction();

} else {
    $conn = getConnTransaction();

    $sql = "SELECT t.*, p.position_id, p.cutting 
                        FROM cutting AS t 
                        INNER JOIN position AS p ON t.worksheet_id = p.worksheet_id 
                        ORDER BY 
                            CASE WHEN t.date_cut IS NULL THEN 0 ELSE 1 END, 
                            p.cutting ASC, 
                            t.date_cut DESC,
                            t.date_out DESC";

    $ct_data = $conn->query($sql);
    $conn->close();
    //$ct_data = fetchAllTransactionByProcess('cutting');
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

}

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

<!--div class="w3-container">
    <h5>Filter</h5>

    <form action method="post">
        <input placeholder="No. Article" name="fArticleId">
        <input type="date" name="fStartDate">
        <input type="date" name="fEndDate">
        <button type="submit">Filter</button>
    </form>
</div-->

<table class="w3-table w3-table-all w3-hide-small w3-small w3-margin-top">
    <thead>
    <tr>
        <th>No</th>
        <th>Cutting No.</th>
        <th>Worksheet No.</th>
        <th>Article ID</th>
        <th>Model</th>
        <th>Location</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th class="w3-center">Qty Cutting</th>
        <th class="w3-center">Tgl Cutting</th>

        <!-- Admin Produksi -->
        <?php if(in_array($role, [0,1,2,3])): ?>
        <th>Embro?</th>
        <th>Sablon?</th>
        <?php endif; ?>


        <th>Surat Jalan</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
        <?php
            $i = 0;

            if ($ct_data->num_rows == 0) {
                echo "<tr>";
                echo "<td colspan='9'>No data found!</td>";
                echo "</tr>";
            }


            while ($ct = $ct_data->fetch_assoc()) {
                ++$i;

                $worksheet = fetchWorksheet($ct['worksheet_id'])->fetch_assoc();
                $article_id = $worksheet['article_id'];
                $article = getArticleById($article_id);

                if (!empty($_POST['fArticleId'])  && $_POST['fArticleId'] !== $article_id) {
                    continue;
                }

                echo "<tr>";
                echo "<td>{$i}</td>";
                echo "<td>{$ct['cutting_id']}</td>";
                echo "<td>{$ct['worksheet_id']}</td>";
                echo "<td>{$article_id}</td>";
                echo "<td>{$article['model_name']}</td>";

                echo "<td>". getCMTNameById($ct['cmt_id']) . "</td>";

                echo "<td>{$ct['date_in']}</td>";
                echo "<td>{$ct['date_out']}</td>";

                echo "<td class='w3-center'>";
                if ($ct['qty_out'] <= 0 && in_array($role, [0,1,2,3,4,7])) {
                    $urlParam = "p=cutting";
                    $urlParam .= "&i=" . $ct['cutting_id'];
                    $urlParam .= "&w=" . $ct['worksheet_id'];
                    echo "<button onclick='openPopupURL2(\"set-qty-out.php?{$urlParam}\", \"qtyOut\", 500, 300)' class='w3-button w3-blue-grey'>" . "<i class='fas fa-plus'></i>" . "</button>";
                } else {
                    echo $ct['qty_out'];
                }
                echo "</td>";

                echo "<td>{$ct['date_cut']}</td>";

                if(in_array($role, [0,1,2,3])):
                    $check = "<span style='font-weight: bold;'><i class='fas fa-fw fa-xl fa-check w3-text-green'></i></span>";
                    $naught = "<span style='font-weight: bold;'><i class='fas fa-fw fa-x w3-text-red'></i></span>";

                    $embroStatus = $sablonStatus = $naught;

                    // CMTs
                    $article = getArticleById($article_id);

                    $cmtEmbro = getCMTNameById($article['embro_cmt_id']);
                    $cmtPrint = getCMTNameById($article['print_cmt_id']);

                    if ($cmtEmbro !== "NO") {
                        $embroStatus = $check . "<br>" . "<span class='w3-tiny'>" . $cmtEmbro . "</span>";
                    }

                    if ($cmtPrint !== "NO") {
                        $sablonStatus = $check . "<br>" . "<span class='w3-tiny'>" . $cmtPrint . "</span>";
                    }

                    echo "<td class='w3-center' style='vertical-align: middle'>" . $embroStatus . "</td>";
                    echo "<td class='w3-center' style='vertical-align: middle'>" . $sablonStatus ."</td>";

                endif;


                echo "<td>";

                // Dropdown SJ Print
                $urlSuratJalanKantor = "/transaction/surat-jalan/?i={$ct['st_id']}&t={$ct['cutting_id']}&w={$ct['worksheet_id']}";
                $urlSuratJalan = "/transaction/surat-jalan/?i={$ct['sj_id']}&t={$ct['cutting_id']}&w={$ct['worksheet_id']}";

                if (checkSuratKantorExistsByTransactionId($ct['cutting_id'])) {
                    $dropdownId = $ct['cutting_id'] . "_sj";

                    $sjPrint = $skPrint = null;

                    if (checkSuratJalanCOPrinted($ct['cutting_id'])) {
                    $skPrint = "&nbsp; <i class='fas fa-fw w3-tiny fa-check'></i>";
                    }

                    if (checkSuratJalanPrinted($ct['cutting_id'])) {
                    $sjPrint = "&nbsp; <i class='fas fa-fw w3-tiny fa-check'></i>";
                    }

                    echo "<button onclick=\"dropdownButton('$dropdownId')\" class='w3-button w3-green'><i class='fas fa-print'></i></button>";
                    echo "<div id='$dropdownId' class='w3-dropdown-content w3-bar-block w3-border'>";
                        echo "<button class='w3-bar-item w3-button' onclick='openPopupURL2(\"$urlSuratJalanKantor\", \"suratJalan\", 800, 500)'>Kantor {$skPrint}</button>";

                        if (checkSuratJalanExistsByTransactionId($ct['cutting_id']) && $_SESSION['user_role'] != 4) {
                            echo "<button class='w3-bar-item w3-button' onclick='openPopupURL2(\"$urlSuratJalan\", \"suratJalan\", 800, 500)'>External {$sjPrint}</button>";
                        }

                        echo "</div>";
                }


                echo "<td>";

                if (getPositionStatus($ct['worksheet_id'], 'cutting') == 0) {
                    if($ct['qty_out'] > 0  && in_array($role, [0,1,2,3,7])) {
                        echo "<button class='w3-button w3-red' onclick='openPopupURL2(\"sendDialog.php?w={$ct['worksheet_id']}&i={$ct['id']}&pi={$ct['cutting_id']}&q={$ct['qty_out']}\", \"sendtocutting\", 500, 400)'><i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
                    }

                } else {
                    echo "<button class='w3-button w3-hover-red w3-red w3-round w3-disabled'><i class=\"fa-solid fa-check\"></i></button>";
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
        $pmId = $ct['cutting_id'];
        $details = fetchWorksheetDetails($worksheetId);
        $articleId = $details['article_id'];



        echo "<thead>";
        echo "<tr class='w3-indigo'>";      // todo: change colour later
        echo "<th>Cutting ID</th>";
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
        echo "<td>Location</td>";
        echo "<td>" . getCMTNameById($ct['cmt_id']) . "</td>";
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
        echo "<td>Cutting Qty</td>";
        echo "<td>{$ct['qty_out']}</td>";
        echo "</tr>";

        echo "<tr>";
        // Action buttons
        echo "<td class='w3-center'>";
        // Dropdown SJ Print
        $urlSuratJalanKantor = "/transaction/surat-jalan/?i={$ct['st_id']}&t={$ct['cutting_id']}&w={$ct['worksheet_id']}";
        $urlSuratJalan = "/transaction/surat-jalan/?i={$ct['sj_id']}&t={$ct['cutting_id']}&w={$ct['worksheet_id']}";

        if (checkSuratKantorExistsByTransactionId($ct['cutting_id'])) {
            $dropdownId = $ct['cutting_id'] . "_sj_m";

            echo "<button class='w3-bar-item w3-button w3-green w3-col s5 m5' onclick='openPopupURL2(\"$urlSuratJalanKantor\", \"suratJalan\", 800, 500)'><i class='fas fa-home'></i></button>";

            echo "<div class='w3-col s1 m1'>&nbsp;</div>";

            if (checkSuratJalanExistsByTransactionId($ct['cutting_id']) && $_SESSION['user_role'] != 4) {
                echo "<button class='w3-bar-item w3-button w3-green w3-col s5 m5' onclick='openPopupURL2(\"$urlSuratJalan\", \"suratJalan\", 800, 500)'><i class='fas fa-print'></i></button>";
            }

        }

        echo "</td>";
        // Send button
        echo "<td class='w3-center'>";
        if (getPositionStatus($ct['worksheet_id'], 'cutting') == 0) {
            echo "<button style='width: 85%;' class='w3-button w3-red' onclick='openPopupURL2(\"sendDialog.php?w={$ct['worksheet_id']}&i={$ct['id']}&pi={$ct['cutting_id']}&q={$ct['qty_out']}\", \"sendtocutting\", 500, 400)'><i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
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
    function dropdownButton(dropdownId) {
        var x = document.getElementById(dropdownId);

        if (x.className.indexOf('w3-show') === -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }
</script>