<?php
session_start();
$uid = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';

$tConn = getConnTransaction();
$pConn = getConnProduction();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List WIP</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>
<script src="/assets/js/utils.js"></script>
<body class="w3-padding-24">
<div class="w3-container classification-content" id="worksheet-modal" style="">

    <div class="w3-top w3-padding-24 w3-light-grey w3-border w3-container">
        <form method="post" action class="w3-row w3-small">

            <input class="w3-input w3-col l2 w3-border" type="text" name="textFilter" id="textFilter" placeholder="No. Artikel / Nama Model">

            <select class="w3-select w3-col l2 w3-border"  name="brandFilter" id="brandFilter">
                <option class="w3-medium" selected value="">Semua Brand</option>
                <?php
                    $sql = "SELECT * FROM brand ORDER BY brand_name ASC";
                    $result = $pConn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($brands = $result->fetch_assoc()) :
                ?>

                <option class="w3-medium" value="<?= $brands['brand_id'] ?>"><?= $brands['brand_name'] ?></option>

                <?php
                        endwhile;
                    }
                ?>
            </select>

            <select class="w3-select w3-col l2 w3-border"  name="posFilter" id="posFilter">
                <option class="w3-medium" selected value="">Semua Transaksi</option>
                <option class="w3-medium" value="0">Worksheet</option>
                <option class="w3-medium" value="1">Pola Marker</option>
                <option class="w3-medium" value="2">Cutting</option>
                <option class="w3-medium" value="3">Embro</option>
                <option class="w3-medium" value="4">Print/Sablon</option>
                <option class="w3-medium" value="5">QC Embro</option>
                <option class="w3-medium" value="6">Sewing</option>
                <option class="w3-medium" value="-2">Transit ke Washing</option>
                <option class="w3-medium" value="7">Washing</option>
                <option class="w3-medium" value="8">Finishing</option>
                <option class="w3-medium" value="9">QC Final</option>
                <option class="w3-medium" value="10">Perbaikan</option>
                <option class="w3-medium" value="11">Gudang</option>
            </select>

            <button class="w3-button w3-col l1 w3-blue-grey" type="submit">Filter <i class="fas fa-fw fa-filter-list"></i></button>
        </form>
    </div>

    <div style="padding-top: 96px; overflow-x: auto;">
        <table class="w3-margin-top w3-table-all w3-small">
            <thead>
            <tr>
                <th rowspan="2" class="w3-center w3-blue-grey" style="vertical-align: middle;">No</th>
                <th rowspan="2" class="w3-center w3-blue-grey" style="vertical-align: middle;">Worksheet No.</th>
                <th rowspan="2" class="w3-center w3-blue-grey" style="vertical-align: middle;">Article ID</th>
                <th rowspan="2" class="w3-center w3-blue-grey" style="vertical-align: middle;">Model</th>
                <th rowspan="2" class="w3-center w3-blue-grey" style="vertical-align: middle;">Tgl Worksheet</th>
                <th rowspan="2" class="w3-center w3-blue-grey" style="vertical-align: middle;">Merk</th>

                <th rowspan="2" class="w3-center w3-blue-grey" style="vertical-align: middle;">Kategori</th>

                <th rowspan="2" class="w3-center w3-blue-grey" style="vertical-align: middle;">Posisi</th>

                <th rowspan="2" class="w3-center w3-blue-grey" style="vertical-align: middle;">Qty Est.</th>

                <th rowspan="2" class="w3-center w3-blue-grey" style="vertical-align: middle;">Qty Cutting</th>

                <th colspan="2" class="w3-center w3-blue-grey" style="vertical-align: middle;">Qty Sewing</th>

                <th colspan="2" class="w3-center w3-blue-grey" style="vertical-align: middle;">Qty Finishing</th>

                <th colspan="5" class="w3-center w3-blue-grey" style="vertical-align: middle;">Qty QC Final</th>

                <th rowspan="2" class="w3-center w3-blue-grey" style="vertical-align: middle;">Qty Masuk Gudang</th>
            </tr>
            <tr>
                <!-- Sewing -->
                <th class="w3-center w3-light-grey w3-border-left">In</th>
                <th class="w3-center w3-light-grey w3-border-right">Out</th>
                <!-- Finishing -->
                <th class="w3-center w3-light-grey w3-border-left">In</th>
                <th class="w3-center w3-light-grey w3-border-right">Out</th>
                <!-- QC Final -->
                <th class="w3-center w3-light-grey w3-border-left">In</th>
                <th class="w3-center w3-light-grey">Out</th>
                <th class="w3-center w3-light-grey" style="vertical-align: middle;">Gagal</th>
                <th class="w3-center w3-light-grey" style="vertical-align: middle;">Hilang</th>
                <th class="w3-center w3-light-grey w3-border-right" style="vertical-align: middle;">Cacat</th>
            </tr>
            </thead>
            <tbody>
            <?php
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet_position.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_transaction.php";
            include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_articles.php";

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Get Filter query
                $textFilter = $_POST['textFilter'];
                $posFilter = $_POST['posFilter'];
                $brandFilter = $_POST['brandFilter'];

                // $dbTransaction = 'suburjaya';    // Local
                $dbTransaction = 'subm6595_sj';    // Production

                // Construct the SQL query
                $sql = "SELECT * FROM worksheet 
                        LEFT JOIN subm6595_sj_transaction.position AS p ON worksheet.worksheet_id = p.worksheet_id 
                        INNER JOIN worksheet_detail ON worksheet.worksheet_id = worksheet_detail.worksheet_id 
                        LEFT JOIN article ON worksheet_detail.article_id = article.article_id
                        WHERE (worksheet_detail.article_id LIKE '%$textFilter%' OR article.model_name LIKE '%$textFilter%')";

                // Check if $posFilter is not empty and not equal to an empty string
                if ($posFilter !== '') {
                    $sql .= " AND (p.position_id = '$posFilter')";
                }

                // Check if $brandFilter is not empty and not equal to an empty string
                if ($brandFilter !== '') {
                    $sql .= " AND (brand_id = '$brandFilter')";
                }

                $sql .= " ORDER BY p.position_id ASC, p.worksheet_id DESC";

            } else {
                $sql = "SELECT * FROM worksheet 
                LEFT JOIN subm6595_sj_transaction.position AS p ON worksheet.worksheet_id = p.worksheet_id 
                INNER JOIN worksheet_detail ON worksheet.worksheet_id = worksheet_detail.worksheet_id 
                LEFT JOIN article ON worksheet_detail.article_id = article.article_id
                ORDER BY p.position_id ASC, p.worksheet_id DESC";
            }

            $result = $pConn->query($sql);

            $worksheets = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($worksheets as $index => $worksheet) {
                $worksheetId = $worksheet['worksheet_id'];
                $details = fetchWorksheetDetails($worksheetId);

                $qtyEstimated = getQtyEstimated($worksheetId);

                $qtyCutting = getQtyCutting($worksheetId);

                $qtySewingIn    = $qtyCutting == 0 ? '-' : $qtyCutting;
                $qtySewingOut   = getQtySewing($worksheetId);

                $qtyFinishingIn    = getQtyFinishing($worksheetId, 'in');
                $qtyFinishingOut   = getQtyFinishing($worksheetId, 'out');

                $qtyFinishingIn     = $qtyFinishingIn == 0 ? '-' : $qtyFinishingIn;

                $qtyQCFinalIn    = getQtyQCFinal($worksheetId, 'in');
                $qtyQCFinalOut   = getQtyQCFinal($worksheetId, 'out');

                $qtyQCFinalIn     = $qtyQCFinalIn == 0 ? '-' : $qtyQCFinalIn;

                $qtyGudang = getQtyGudangIn($worksheetId);

                $qtyGagal  = getQtyGagal($worksheetId);
                $qtyCacat  = getQtyCacat($worksheetId);
                $qtyHilang = getQtyHilang($worksheetId);

                echo "<tr>";
                echo "  <td class='w3-center' style='vertical-align: middle;'>" . ($index + 1) . "</td>";
                echo "  <td class='w3-center' style='vertical-align: middle;'>{$worksheet['worksheet_id']}</td>";
                echo "  <td class='w3-center' style='vertical-align: middle;'>{$worksheet['article_id']}</td>";
                echo "  <td style='vertical-align: middle;'>{$worksheet['model_name']}</td>";
                echo "  <td class='w3-center' style='vertical-align: middle;'>{$worksheet['worksheet_date']}</td>";

                $brand = getBrandNameById($worksheet['brand_id']);
                echo "  <td class='w3-center' style='vertical-align: middle;'>{$brand}</td>";

                $category = getCategoryByArticleId($worksheet['article_id']);
                echo "  <td class='w3-center' style='vertical-align: middle;'>{$category}</td>";

                $pos = parseWorksheetPosition(getWorksheetPosition($worksheetId));
                $url = "/transaction/" . strtolower(str_replace(" ", "-", $pos));
                $url = str_replace("unknown", "", $url); // remove link if unknown
                // Link to process view
                if ($pos == 'SEWING') {
                    $cmt_sewing = getSewingCMTByWorksheetId($worksheet['worksheet_id']);
                } else {
                    $cmt_sewing = '';
                }
                echo "<td class='w3-center' style='vertical-align: middle;'><a href='$url' target='_blank'>{$pos}</a><br>{$cmt_sewing}</td>";


                echo "<td class='w3-center' style='vertical-align: middle;'>$qtyEstimated</td>";

                echo "<td class='w3-center w3-border-left' style='vertical-align: middle;'>$qtyCutting</td>";

                $nPos = getWorksheetPosition($worksheetId);
                $qtySewingIn = in_array($nPos, [-2, 6, 7, 8, 9, 10, 11]) ? $qtyCutting : "-";
                echo "<td class='w3-center w3-border-left' style='vertical-align: middle;'>$qtySewingIn</td>";
                echo "<td class='w3-center w3-border-right' style='vertical-align: middle;'>$qtySewingOut</td>";

                // Finishing
                echo "<td class='w3-center w3-border-left' style='vertical-align: middle;'>$qtyFinishingIn</td>";
                echo "<td class='w3-center w3-border-right' style='vertical-align: middle;'>$qtyFinishingOut</td>";

                // QC Final
                echo "<td class='w3-center w3-border-left' style='vertical-align: middle;'>$qtyQCFinalIn</td>";
                echo "<td class='w3-center' style='vertical-align: middle;'>$qtyQCFinalOut</td>";

                echo "<td class='w3-center' style='vertical-align: middle;'>$qtyGagal</td>";
                echo "<td class='w3-center' style='vertical-align: middle;'>$qtyHilang</td>";
                echo "<td class='w3-center w3-border-right' style='vertical-align: middle;'>$qtyCacat</td>";

                // Gudang
                echo "<td class='w3-center' style='vertical-align: middle;'>$qtyGudang</td>";




                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<?php


function getQtyEstimated($wid) {
    $conn = getConnProduction();

    $sql = "SELECT qty FROM worksheet_detail WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty'];
}

function getQtyCutting($wid) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_out FROM cutting WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty_out'];
}

function getQtySewing($wid) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_out FROM sewing WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty_out'];
}

function getQtyFinishing($wid, $gateway) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_{$gateway} AS qty FROM finishing WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }
    return $result->fetch_assoc()['qty'];
}

function getQtyQCFinal($wid, $gateway) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_{$gateway} AS qty FROM qc_final WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }
    return $result->fetch_assoc()['qty'];
}

function getQtyGudangIn($wid) {
    $conn = getConnTransaction();

    $sql = "SELECT qty FROM warehouse WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty'];
}

function getQtyGagal($wid) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_fail AS qty FROM qc_final WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty'];
}

function getQtyHilang($wid) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_missing AS qty FROM qc_final WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty'];
}

function getQtyCacat($wid) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_defect AS qty FROM qc_final WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty'];
}

function getSewingCMTByWorksheetId($wid) {
    $conn = getConnTransaction();
    $sql = "SELECT cmt_id FROM sewing WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);
    $cmt_id = $result->fetch_assoc()['cmt_id'];
    $conn->close();

    $conn = getConnProduction();
    $sql = "SELECT cmt_name FROM cmt WHERE cmt_id = '$cmt_id'";
    $result = $conn->query($sql);
    $cmt_name = $result->fetch_assoc()['cmt_name'];

    return $cmt_name;
}

function getCategoryByArticleId($aid) {
    $conn = getConnProduction();

    $sql = "SELECT category.category_name 
            FROM article
            INNER JOIN main_category AS category ON article.category_id = category.category_id
            WHERE article_id = '$aid'";
    $result = $conn->query($sql);

    return $result->fetch_assoc()['category_name'];
}

?>