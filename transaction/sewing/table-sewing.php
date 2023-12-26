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

<style>
    td {
        text-align: center !important;
    }

    .jt-padding-tb {
        padding-top: 16px;
        padding-bottom: 16px;
    }
    .jt-padding-tb-8 {
        padding-top: 8px;
        padding-bottom: 8px;
    }
</style>

<?php
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet_position.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_transaction.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_classification.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_articles.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet.php";
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_surat_jalan.php";

$ct_data = fetchAllTransactionByProcess('sewing');
$i = 0;

?>

<div class="w3-row w3-hide-large w3-hide-medium">
    <h6>Mobile device viewing is disabled, please use a browser on computer.</h6>
    <h6>Tampilan perangkat seluler dinonaktifkan, harap gunakan browser di komputer.</h6>
</div>


<div class="w3-row w3-hide-small w3-small">
    <div class='w3-col w3-row l12 m12 s12 w3-white w3-border w3-border-light-gray w3-center'>
        <div class="w3-col l3 m3 s12 jt-padding-tb" style="font-weight: bold;">
            <div class="w3-col l2 m2 s12 jt-padding-tb" style="font-weight: bold;">No.</div>
            <div class="w3-col l5 m5 s12 jt-padding-tb" style="font-weight: bold;">Sewing ID</div>
            <div class="w3-col l5 m5 s12 jt-padding-tb" style="font-weight: bold;">Worksheet ID</div>
        </div>

        <div class="w3-col l1 m1 s12 jt-padding-tb" style="font-weight: bold;">Article ID</div>
        <div class="w3-col l1 m1 s12 jt-padding-tb" style="font-weight: bold;">Model</div>

        <div class="w3-col l1 m1 s12 jt-padding-tb" style="font-weight: bold;">CMT</div>

        <div class="w3-col l2 m2 s12 jt-padding-tb w3-row" style="font-weight: bold;">
            <div class="w3-col l4 m4 s12 jt-padding-tb" style="font-weight: bold;">Qty In</div>
            <div class="w3-col l4 m4 s12 jt-padding-tb" style="font-weight: bold;">Qty Out</div>
            <div class="w3-col l4 m4 s12 jt-padding-tb" style="font-weight: bold;">Qty Hilang</div>
        </div>

        <div class="w3-col l1 m1 s12 jt-padding-tb" style="font-weight: bold;">Start Date</div>
        <div class="w3-col l1 m1 s12 jt-padding-tb" style="font-weight: bold;">End Date</div>
        <div class="w3-col l1 m1 s12 jt-padding-tb" style="font-weight: bold;">Wash?</div>


        <div class="w3-col l1 m1 s12 jt-padding-tb w3-row" style="font-weight: bold;">
            <div class="w3-col l6 m6 s12 jt-padding-tb" style="font-weight: bold;">Actions</div>
            <div class="w3-col l6 m6 s12 jt-padding-tb" style="font-weight: bold;">Lempar</div>
        </div>
    </div>
    <?php
        $index = 0;
        while ($ct = $ct_data->fetch_assoc()) {
            ++$index;
            $worksheet = fetchWorksheet($ct['worksheet_id'])->fetch_assoc();
            $article_id = $worksheet['article_id'];
            $article = getArticleById($article_id);

            $cmtName = getCMTNameById($ct['cmt_id']);

            $colour = ($index % 2) == 0 ? 'light-grey' : 'white';

            // Sewing Out Data
            $sewingOutData = fetchSewingOutRecords($ct['sewing_id']);
            $detailparam = '';
            $chevron = "";

            if ($sewingOutData->num_rows > 0) {
                $detailparam = "class='w3-hover-text-red' style='cursor: pointer;' onclick='toggleDetail(\"{$ct['sewing_id']}\")'";
                $chevron = "<i class='fas fa-chevron-down'></i>";

            }

            echo "<div class='w3-col w3-row l12 m12 s12 w3-{$colour} w3-center w3-border w3-border-light-gray'>";

            echo "<div class='w3-col l3 m3 s12 jt-padding-tb-8 w3-row'>";
                echo "<div class='w3-col l2 m2 s12 jt-padding-tb-8'>{$index}<br><span $detailparam>$chevron</span></div>";
                echo "<div class='w3-col l5 m5 s12 jt-padding-tb-8'>{$ct['sewing_id']}</div>";
                echo "<div class='w3-col l5 m5 s12 jt-padding-tb-8'>{$ct['worksheet_id']}</div>";
            echo "</div>";

            echo "<div class='w3-col l1 m1 s12 jt-padding-tb-8'>{$article_id}</div>";
            echo "<div class='w3-col l1 m1 s12 jt-padding-tb-8'>{$article['model_name']}</div>";

            echo "<div class='w3-col l1 m1 s12 jt-padding-tb-8'>{$cmtName}</div>";

            echo "<div class='w3-col l2 m2 s12 jt-padding-tb-8 w3-row'>";
                echo "<div class='w3-col l4 m4 s12 jt-padding-tb-8'>{$ct['qty_in']}</div>";
                echo "<div class='w3-col l4 m4 s12 jt-padding-tb-8'>{$ct['qty_out']}</div>";
                echo "<div class='w3-col l4 m4 s12 jt-padding-tb-8'>{$ct['qty_missing']}</div>";
            echo "</div>";

            echo "<div class='w3-col l1 m1 s12 jt-padding-tb-8'>{$ct['date_in']}</div>";
            echo "<div class='w3-col l1 m1 s12 jt-padding-tb-8'>{$ct['date_out']}&nbsp;</div>";

            $isWash = checkIsWash($article_id) == 1 ? "<i class='fas fa-fw fa-check w3-text-green fa-xl'></i>" : "<i class='fas fa-fw fa-x w3-text-red fa-lg'></i>";


            echo "<div class='w3-col l1 m1 s12 jt-padding-tb-8'>$isWash</div>";

            echo "<div class='w3-col l1 m1 s12 jt-padding-tb-8 w3-row'>";
                echo "<div class='w3-col l6 m6 s12 jt-padding-tb-8'>";
                $qtyCurrentTotal = $ct['qty_out'] + $ct['qty_missing'];

                if ($qtyCurrentTotal != $ct['qty_in'] && in_array($role, [0,1,2,3,7])) {
                    $urlParam = "p=sewing";
                    $urlParam .= "&i=" . $ct['sewing_id'];
                    $urlParam .= "&w=" . $ct['worksheet_id'];
                    $urlParam .= "&q=" . $ct['qty_in'];
                    echo "<button onclick='openPopupURL2(\"set-qty-out.php?{$urlParam}\", \"qtyOut\", 500, 700)' class='w3-button w3-blue-grey'>" . "<i class='fas fa-plus'></i>" . "</button>";
                }
                $urlSuratTerima = "/transaction/surat-jalan/?i={$ct['sj_id']}&t={$ct['sewing_id']}&w={$ct['worksheet_id']}";

                if (checkSuratJalanExistsByTransactionId($ct['sewing_id'])) {
                    $isPrint = "";

                    if (checkSuratJalanPrinted($ct['sewing_id'])) {
                        $isPrint = "&nbsp;<i class='fas w3-tiny fa-fw fa-check'></i>";
                    }

                    echo "<button class='w3-button w3-green' onclick='openPopupURL2(\"$urlSuratTerima\", \"suratJalan\", 800, 500)'><i class='fas fa-print'></i>{$isPrint}</button>";

                }
                echo "</div>";

                echo "<div class='w3-col l6 m6 s12 jt-padding-tb-8'>";
                if (getWorksheetPosition($ct['worksheet_id']) == 6) {
                    if (($ct['qty_out'] + $ct['qty_missing']) == $ct['qty_in']) {
                        if (in_array($role, [0,1,2,3,7])) {
                            echo "<button class='w3-button w3-red' onclick='openPopupURL2(\"sendDialog.php?w={$ct['worksheet_id']}&i={$ct['id']}&pi={$ct['sewing_id']}&q={$ct['qty_out']}\", \"sendto\", 500, 400)'><i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
                        }
                    }

                } else {
                    echo "<button class='w3-button w3-hover-red w3-red w3-disabled'><i class='fas fa-check'></i></button>";
                }
                echo "</div>";
            echo "</div>";


            echo "<div class='w3-col w3-row l12 m12 s12 w3-pale-blue w3-center {$ct['sewing_id']}' style='display: none; padding-top:8px; padding-bottom: 24px;'>";
            while ($out = $sewingOutData->fetch_assoc()) {
                echo "<div class='w3-col l6 m6'>&nbsp;</div>";


                echo "<div class='w3-col l2 m2 jt-padding-tb-8 w3-row'>";
                    echo "<div class='w3-col l4 m4 s12'>&nbsp;</div>";
                    echo "<div class='w3-col l4 m4 s12'>{$out['qty_out']}</div>";
                    echo "<div class='w3-col l4 m4 s12'>{$out['qty_missing']}</div>";
                echo "</div>";

                $description = $out['description'] == "" ? "&nbsp;" : $out['description'];

                echo "<div class='w3-col l1 m1 jt-padding-tb-8'>{$description}</div>";
                echo "<div class='w3-col l1 m1 jt-padding-tb-8'>{$out['datestamp']}</div>";
                echo "<div class='w3-col l2 m2'>&nbsp;</div>";
            }
            echo "</div>";


        }

    ?>

</div>


<!--table class="w3-table w3-table-all">
    <thead class="">
    <tr class="">
        <th  style="text-align: center; vertical-align: middle;">No</th>
        <th  style="text-align: center; vertical-align: middle;">Sewing No.</th>
        <th  style="text-align: center; vertical-align: middle;">Worksheet No.</th>
        <th  style="text-align: center; vertical-align: middle;">Article No.</th>
        <th  style="text-align: center; vertical-align: middle;">Qty In</th>
        <th  style="text-align: center; vertical-align: middle;">Qty Out</th>
        <th  style="text-align: center; vertical-align: middle;">Qty Hilang</th>
        <th  style="text-align: center; vertical-align: middle;">Start Date</th>
        <th  style="text-align: center; vertical-align: middle;">End Date</th>
        <th  style="text-align: center; vertical-align: middle;">Actions</th>
        <th  style="text-align: center; vertical-align: middle;">Send to</th>
    </tr>
    </thead>
    <tbody>

        <?php

            /*
            if ($ct_data->num_rows == 0) {
                echo "<tr>";
                echo "<td colspan='8'>No data found!</td>";
                echo "</tr>";
            }


            while ($ct = $ct_data->fetch_assoc()) {

                $worksheet = fetchWorksheet($ct['worksheet_id'])->fetch_assoc();
                $article_id = $worksheet['article_id'];

                ++$i;
                //echo "<tr>";
                echo "<tr onclick='toggleDetail(\"{$ct['sewing_id']}\")' style='cursor: pointer;'>";
                echo "<td>{$i}</td>";
                echo "<td>{$ct['sewing_id']}</td>";
                echo "<td>{$ct['worksheet_id']}</td>";
                echo "<td>{$article_id}</td>";

                echo "<td>{$ct['qty_in']}</td>";
                echo "<td>";
                $qtyCurrentTotal = $ct['qty_out'] + $ct['qty_missing'];
                echo $ct['qty_out'];
                echo "</td>";

                echo "<td>";
                if ($ct['qty_out'] > 0) {
                    echo $ct['qty_missing'];
                }
                echo "</td>";


                echo "<td>{$ct['date_in']}</td>";
                echo "<td>{$ct['date_out']}</td>";

                echo "<td>";
                if ($qtyCurrentTotal != $ct['qty_in']) {
                    $urlParam = "p=sewing";
                    $urlParam .= "&i=" . $ct['sewing_id'];
                    $urlParam .= "&w=" . $ct['worksheet_id'];
                    $urlParam .= "&q=" . $ct['qty_in'];
                    echo "<button onclick='openPopupURL2(\"set-qty-out.php?{$urlParam}\", \"qtyOut\", 500, 700)' class='w3-button w3-blue-grey'>" . "<i class='fas fa-plus'></i>" . "</button>";
                }

                $urlSuratTerima = "/transaction/surat-jalan/?i={$ct['sj_id']}&t={$ct['sewing_id']}&w={$ct['worksheet_id']}";
                if (checkSuratJalanExistsByTransactionId($ct['sewing_id'])) {
                    echo "<button class='w3-button w3-green' onclick='openPopupURL2(\"$urlSuratTerima\", \"suratJalan\", 800, 500)'><i class='fas fa-print'></i></button>";
                }
                echo "</td>";


                echo "<td>";
                if (getWorksheetPosition($ct['worksheet_id']) == 6) {
                    if (($ct['qty_out'] + $ct['qty_missing']) == $ct['qty_in']) {
                        echo "<button class='w3-button w3-red' onclick='openPopupURL2(\"sendDialog.php?w={$ct['worksheet_id']}&i={$ct['id']}&pi={$ct['sewing_id']}&q={$ct['qty_in']}\", \"sendto\", 500, 400)'><i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
                    }

                } else {
                    echo "<button class='w3-button w3-hover-red w3-red w3-disabled'><i class='fas fa-check'></i></button>";
                }
                echo "</td>";

                echo "</tr>";
                // Detail of sewing out
                $sewingOutData = fetchSewingOutRecords($ct['sewing_id']);


                while ($out = $sewingOutData->fetch_assoc()) {
                    echo "<tr class='w3-pale-blue w3-small {$ct['sewing_id']}' id='detail-{$ct['sewing_id']}'>";
                    echo "<td colspan='5'>&nbsp;</td>";
                    echo "<td>{$out['qty_out']}</td>";
                    echo "<td>{$out['qty_missing']}</td>";
                    echo "<td>&nbsp;</td>";
                    echo "<td>{$out['datestamp']}</td>";

                    echo "<td colspan='2'>&nbsp;</td>";
                    echo "</tr>";
                }
                echo "</div>";


            }
            */

        ?>

    </tbody>
</table-->

</body>
</html>


<script>
    function toggleDetail(sewing_id) {
        var details = document.getElementsByClassName(sewing_id);

        for (var i = 0; i < details.length; i++) {
            var displayValue = details[i].style.display;
            if (displayValue === 'none') {
                details[i].style.display = 'block';
            } else {
                details[i].style.display = 'none';
            }
        }
    }
</script>

<?php

function checkIsWash($article_id) {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
    $conn = getConnProduction();
    $sql = "SELECT * FROM article_wash WHERE article_id = '$article_id'";
    $result = $conn->query($sql);

    $isWash = 1;

    if ($result->num_rows == 1) {
        while ($row = $result->fetch_assoc()) {
            if ($row['wash_id'] == "NOX") {
                return 0;

            }
        }
    }

    return $isWash;

}

?>