<?php
session_start();

$uid = $_SESSION['user_id'];
$username = $_SESSION['username'];

if(!isset($_POST['fDate'])) {
    exit();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_users.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

$userData = getUserDataByID($uid);

$fDate = $_POST['fDate'];
$fLoc = $_POST['fLocation'];


$sql = "SELECT * 
        FROM pola_marker AS pm 
        INNER JOIN
          cutting AS ct ON pm.worksheet_id = ct.worksheet_id
        WHERE 
          Date(pm.date_out) = '$fDate'";

if ($_POST['fLocation'] != '') {
    $sql .= " AND ct.cmt_id = '$fLoc'";
    $fLocName = getCMTNameById($fLoc);
} else {
    $fLocName = 'Cutting';
}

$conn = getConnTransaction();
$result = $conn->query($sql);


$randomString = "suratjalan" . date('Ymd') . $_SESSION['username'] ;
$sha256Hash = hash('sha256', $randomString); // Generate the SHA-256 hash

?>


<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan: Pola Marker (<?= $fDate ?>)</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<?php
$printType = "LX";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $printType = $_POST['printType'];
}

$printNormalSelected = $printType == "NORMAL" ? "selected" : "";
$printLXSelected = $printType == "LX" ? "selected" : "";
?>

<style>
    @media print {
        @page {
        <?php if ($printType == "LX"): ?>
            size: 210mm 145mm; /* Adjust the size according to the dot matrix paper size */
        <?php endif; ?>

            margin: 0.1in; /* Add margins to accommodate the printer's limitations */
        }
    }

    .print-show {
        display: none;
    }

    @media print {
        .print-hide {
            display: none;
        }

        .print-show {
            display: block;
        }
    }
</style>

<body>


<div class="w3-container w3-padding-16 w3-display-container" id="contentToPrint" style="padding-left: 64px; padding-right: 64px">
    <div class="w3-padding w3-right-align print-show">
        <span class=""><b>CV. SUBUR JAYA</b></span><br>
        <span class=" w3-small">Jl. Moch. Ramdhan 56, Bandung | 0895-4042-55456</span>
    </div>

    <div class="w3-padding w3-right-align print-hide w3-hide-large">
        <span class=""><b>CV. SUBUR JAYA</b></span><br>
        <span class=" w3-small">Jl. Moch. Ramdhan 56, Bandung | 0895-4042-55456</span>
    </div>

    <!-- HEADER -->
    <div class="w3-border w3-border-black w3-padding w3-row">
        <div class="w3-col l6 m6 s6">
            <span><strong>SURAT Jalan</strong></span><br>
            <span>Pola Marker >>> Cutting</span>

        </div>
        <div class="w3-col l3 m3 s3">
            <span class="w3-small">No. Bukti</span><br>
            <span class="" style="font-weight: bold">SPM<?= date('Ymd', strtotime($fDate)) ?>-ALL</span>
        </div>
        <div class="w3-col l3 m3 s3">
            <span class="w3-small">Tanggal kirim</span><br>
            <input type="date" class="date-input" style="border:none; font-weight: bold" value="<?= date('Y-m-d') ?>">
        </div>
    </div>

    <!-- CONTENT -->
    <div class="w3-padding">
        <div class="w3-row">
            <div class="w3-col l8 m8 s8">
                &nbsp;
            </div>
            <div class="w3-col l4 m4 s4 w3-small">
                <span>Kepada Yth. </span><br>
                <span>Bapak/Ibu: <?= $fLocName ?></span>
            </div>
        </div>
    </div>

    <!-- ITEMS TABLE -->
    <div class="">
        <div class="w3-row">
            <table class="w3-table w3-small">
                <thead>
                <tr class=" w3-border w3-border-black">
                    <th style="width: 5%;" class="w3-center">#</th>
                    <th style="width: 20%;">No. Artikel</th>
                    <th style="width: 5%;">Qty</th>
                    <th style="width: 40%;">Style/Model</th>
                    <th style="width: 20%;">Merk</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $i = 0;
                    while ($data = $result->fetch_assoc()) {
                        ++$i;

                        $worksheet = fetchWorksheetData($data['worksheet_id']);
                        $aid = $worksheet['article_id'];
                        $article = getArticleById($aid);
                        $aName = $article['model_name'];
                        $aBrand = getBrandNameById($article['brand_id']);

                        echo "<tr>";
                        echo "<td class='w3-center'>$i</td>";
                        echo "<td>{$aid}</td>";
                        echo "<td>1</td>";
                        echo "<td>$aName</td>";
                        echo "<td>$aBrand</td>";
                        echo "</tr>";
                    }
                ?>



                <tr class=" w3-border-bottom w3-border-black">
                    <th class="w3-right-align" colspan="2">Jumlah: </th>
                    <td><?= $i ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="w3-row w3-small">
        <div class="w3-col l4 m4 s4 w3-center">
            <p style="margin-bottom: 30px;">Dibuat oleh,</p>
            <span><?= $userData['first_name'] . " " . $userData['last_name'] ?></span>
        </div>
        <div class="w3-col l4 m4 s4 w3-center">
            <p style="margin-bottom: 30px;">Diketahui oleh,</p>
            <span>(<span style="display: inline-block; width: 96px;"></span>)</span>
        </div>
        <div class="w3-col l4 m4 s4 w3-center">
            <p style="margin-bottom: 30px;">Diterima oleh,</p>
            <span>(<span style="display: inline-block; width: 96px;"></span>)</span>
        </div>
    </div>

    <div class="w3-margin-top w3-small" style="font-weight: bold">
        Jika terdapat selisih antara jumlah aktual dan surat jalan (kurang),
        komplain diterima <u>MAX. 3 HARI</u> dari tanggal barang di terima.
    </div>

    <!-- FOOTER BAR -->
    <div class="w3-bar print-hide w3-margin-top w3-border-top w3-border-bottom w3-border-black w3-tiny w3-center">
        <?= $sha256Hash ?>
    </div>

    <div class="w3-container print-hide w3-right" style="margin-top: 64px;">
        <form action method="post">
            <input hidden value="<?= $fDate ?>" name="fDate">
            <input hidden value="<?= $fLoc ?>" name="fLocation">
            <select name="printType">
                <option value="LX" <?= $printLXSelected ?>>LX Printer</option>
                <option value="NORMAL"<?= $printNormalSelected ?>>Normal Printer</option>
            </select>
            <button type="submit">Pilih Format</button>
        </form>


        <button class="w3-button w3-blue-grey w3-padding w3-bar" onclick="printPage()">
            Print <?= $printType ?> &nbsp;
            <i class="fas fa-print"></i>
        </button>
    </div>

</div>

</body>

<script>
    function printPage() {
        window.print()
    }
</script>



</html>
