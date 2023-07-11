<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_users.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet_position.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';

$uid = $_SESSION['user_id'];
$username = $_SESSION['username'];

$userData = getUserDataByID($uid);

if (isset($_GET['id'])) {
    $sjid = $_GET['id'];

    $sj_data = getSuratJalanDetailBySJID($sjid);
    $sjm_data = fetchSuratJalanMultiData($sjid);

    if ($sjm_data->num_rows < 1) {
        echo "No data received";
        exit();
    }

    $refProcess = $sjm_data->fetch_assoc()['transaction_id'];


    $refWorksheetId = getWorksheetIdByProcessId($refProcess);


    $source = $sj_data['source'];
    $destination = $sj_data['destination'];

    // other variables
    $title = 'Surat Jalan';


    $destination = parseWorksheetPosition($destination);
    $source = parseWorksheetPosition($source);

    $targetProcess = strtolower($destination);
    $receiver_name = getCMTNameById(getCMTId($refWorksheetId, $targetProcess));


} else {
    echo "No data received";
    exit();
}


function getCMTId($worksheet_id, $processName) {
    $conn = getConnTransaction();
    $sql = ($processName != "kantor") ? "SELECT cmt_id FROM $processName WHERE worksheet_id = '$worksheet_id'" : "SELECT cmt_id FROM cutting WHERE worksheet_id = '$worksheet_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc()['cmt_id'];
    return $row;
}



// todo: finalise format of sha256 unique id
$randomString = "suratjalanmulti" . date('Ymd') . $_SESSION['username'] ;
$sha256Hash = hash('sha256', $randomString); // Generate the SHA-256 hash
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>

<style>
    @page {
        size: 210mm 145mm; /* Adjust the size according to the dot matrix paper size */
        margin: 0.1in; /* Add margins to accommodate the printer's limitations */
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

<body class="w3-monospace">

    <div class="w3-container w3-padding-16 w3-display-container" style="padding-left: 64px; padding-right: 64px">
        <div class="w3-padding w3-right-align print-show">
            <span class="w3-monospace"><b>CV. SUBUR JAYA</b></span><br>
            <span class="w3-monospace w3-small">Jl. Moch. Ramdhan 56, Bandung</span>
        </div>

        <div class="w3-padding w3-right-align print-hide w3-hide-large">
            <span class="w3-monospace"><b>CV. SUBUR JAYA</b></span><br>
            <span class="w3-monospace w3-small">Jl. Moch. Ramdhan 56, Bandung</span>
        </div>

        <!-- HEADER -->
        <div class="w3-border w3-border-black w3-padding w3-row">
            <div class="w3-col l6 m6 s6">
                <span><strong><?= $title ?></strong></span><br>
                <span>
                    <?= $source ?>
                    >>>
                    <?= $destination ?>
                </span>

            </div>
            <div class="w3-col l3 m3 s3">
                <span class="w3-small">No. Bukti</span><br>
                <span class="" style="font-weight: bold"><?= $sjid ?></span>
            </div>
            <div class="w3-col l3 m3 s3">
                <span class="w3-small">Tanggal Kirim</span><br>
                <span class="" style="font-weight: bold"><?= date('Y-m-d') ?></span>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="w3-padding">
            <div class="w3-row">
                <div class="w3-col l8 m8 s8">
                    &nbsp;
                </div>
                <div class="w3-col l4 m4 s4 w3-small">
                    <span>Kepada Yth.</span><br>
                    <span>Bapak/Ibu: <?= $receiver_name ?></span>
                </div>
            </div>
        </div>

        <!-- ITEMS TABLE -->
        <div class="">
            <div class="w3-row">
                <table class="w3-table w3-small">
                    <thead>
                    <tr class=" w3-border w3-border-black">
                        <th style="width: 20%;">No. Artikel</th>
                        <th style="width: 5%;">Qty</th>
                        <th style="width: 35%;">Style/Model</th>
                        <th style="width: 15%;">Merk</th>
                        <th style="width: 25%;">Keterangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $qtyTotal = 0;
                    if (!empty($sjm_data)) {
                        // Iterate over each selected row
                        foreach ($sjm_data as $row) {
                            // $worksheetId = $row['worksheetId'];
                            $articleId = $row['article_id'];
                            $article = getArticleById($articleId);
                            $qty = $row['qty'];
                            echo "<tr>";
                            echo "<td>$articleId</td>";
                            echo "<td>$qty</td>";
                            echo "<td>". $article['model_name'] ."</td>";
                            echo "<td>". getBrandNameById($article['brand_id']) ."</td>";
                            echo "<td contenteditable></td>";
                            echo "</tr>";

                            $qtyTotal += $qty;
                        }
                    } else {
                        echo "<tr>";
                        echo "<td>No data received!</td>";
                        echo "</tr>";
                    }


                    ?>

                    <tr class=" w3-border-bottom w3-border-black">
                        <th class="w3-right-align">Jumlah: </th>
                        <td><?= $qtyTotal ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="w3-row w3-small print-show">
            <div class="w3-col l4 m4 s4 w3-center">
                <p style="margin-bottom: 30px;">Dibuat oleh,</p>
                <span><?= $userData['first_name'] . " " . $userData['last_name'] ?></span>
            </div>
            <div class="w3-col l4 m4 s4 w3-center">
                <p style="margin-bottom: 30px;">Diketahui oleh,</p>
                <span>Manager Produksi</span>
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
            <button class="w3-button w3-blue-grey w3-padding w3-bar" onclick="window.print()">
                Print &nbsp;
                <i class="fas fa-print"></i>
            </button>
        </div>

    </div>

</body>



</html>
