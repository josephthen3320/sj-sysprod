<?php
session_start();

$uid = $_SESSION['user_id'];
$username = $_SESSION['username'];

if(!isset($_GET['i'])) {
    exit();
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_users.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_surat_jalan.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';

// Get all GET values
$sjid = $_GET['i'];
$wid = $_GET['w'];
$tid = $_GET['t'];

$worksheet = fetchWorksheetData($wid);
$article_id = $worksheet['article_id'];

$id = getSuratJalanInternalId($sjid);

$sjData = getSuratJalanDetail($id);

$userData = getUserDataByID($uid);

$source = getProcessName($sjData['source']);
$destination = getProcessName($sjData['destination']);

$targetProcess = strtolower($destination);

switch ($targetProcess) {
    case "print / sablon":
        $targetProcess = "print_sablon";
        break;
    case "sewing & cmt":
        $targetProcess = "sewing";
        break;
}

// fetch article data
$articleData = getArticleById($sjData['article_id']);
$brand = getBrandNameById($articleData['brand_id']);

// todo: fetch customer_name from worksheet_detail as receiver_name

$customer = ($sjData['destination'] != -1) ? getCMTNameById(getCMTId($wid, $targetProcess)) : "Kantor";
$customer = ($sjData['destination'] != -2) ? getCMTNameById(getCMTId($wid, $targetProcess)) : "Transit";
$customer = ($sjData['destination'] != -2) ? getCMTNameById(getCMTId($wid, $targetProcess)) : "Transit";

switch ($sjData['destination']) {
    case -1:
        $customer = "Kantor";
        break;
    case -2:
        $customer = "Transit";
        break;
    case 11:
        $customer = "Gudang Jadi";
        $destination = "Gudang";
        break;
}

$receiver_name = $customer;



//$customer = $receiver_name = 'John Doe';



// todo: finalise format of sha256 unique id
$randomString = "suratjalan" . date('Ymd') . $_SESSION['username'] ;
$sha256Hash = hash('sha256', $randomString); // Generate the SHA-256 hash


function getCMTId($worksheet_id, $processName) {
    $conn = getConnTransaction();

    /* todo: check overall logic */
    $processName = str_replace(" ", "_", $processName);

    if (substr($processName, 0, 2) === 'qc') {
        return 'SJ';
    }

    $sql = ($processName != "kantor") ? "SELECT cmt_id FROM $processName WHERE worksheet_id = '$worksheet_id'" : "SELECT cmt_id FROM cutting WHERE worksheet_id = '$worksheet_id'";

    switch ($processName) {
        case "kantor":
            $sql = "SELECT cmt_id FROM cutting WHERE worksheet_id = '$worksheet_id'";
            break;
        case "warehouse":
            return "Gudang";
            break;
        default:
            $sql = "SELECT cmt_id FROM $processName WHERE worksheet_id = '$worksheet_id'";
            break;
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0 && $processName != 'warehouse') {
        $row = $result->fetch_assoc()['cmt_id'];
    } else {
        $row = null;
    }

    return $row;
}

?>


<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan: <?= $sjid?></title>
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
                <span><strong>SURAT <?= $sjData['type'] == '1' ? 'JALAN' : 'TERIMA' ?></strong></span><br>
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
                <span class="w3-small">Tanggal <?= $sjData['type'] == '1' ? 'kirim' : 'terima' ?></span><br>
                <span class="print-show" style="font-weight: bold"><?= $sjData['send_date'] ?></span>
                <input type="date" class="date-input print-hide" style="border:none; font-weight: bold" value="<?= $sjData['send_date'] ?>">

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var dateInput = document.querySelector('.date-input');

                        dateInput.addEventListener('click', function() {
                            this.readOnly = false;
                            this.classList.add('editable'); // Add a class to style the editable state
                        });

                        dateInput.addEventListener('blur', function() {
                            this.readOnly = true;
                            this.classList.remove('editable'); // Remove the editable class

                            // Get the new date value from the input
                            var newDate = this.value;
                            var sjid = '<?= $sjid ?>';
                            var oldDate = '<?= $sjData['send_date'] ?>';

                            // Send the data using Ajax
                            $.ajax({
                                type: 'POST',
                                url: 'update_date.php', // Replace this with the actual PHP file to handle the update
                                data: {
                                    sjid: sjid,
                                    newDate: newDate,
                                    oldDate: oldDate
                                },
                                success: function(response) {
                                    // Handle the successful response here (if needed)
                                    console.log(response); // Log the response for debugging

                                    // Reload the page after 0.2 seconds (200 milliseconds)
                                    setTimeout(function() {
                                        location.reload();
                                    }, 200);
                                },
                                error: function(xhr, status, error) {
                                    // Handle errors here (if any)
                                    console.log(xhr.responseText); // Log the error response for debugging
                                }
                            });
                        });
                    });
                </script>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="w3-padding">
            <div class="w3-row">
                <div class="w3-col l8 m8 s8">
                    &nbsp;
                </div>
                <div class="w3-col l4 m4 s4 w3-small">
                    <span><?= $sjData['type'] == '1' ? 'Kepada Yth.' : 'Dari' ?></span><br>
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
                    <tr class=" w3-border-bottom w3-border-black">
                        <td><?= $sjData['article_id'] ?></td>
                        <td><?= $sjData['qty'] ?></td>
                        <td><?= $articleData['model_name'] ?></td>
                        <td><?= $brand ?></td>
                        <td contenteditable id="<?= $sjid ?>"><?= $sjData['description'] ?></td>
                    </tr>

                    <script>
                        // Assuming you're using the jQuery library for AJAX functionality
                        $(document).ready(function() {
                            // Create a delay variable
                            var delay = null;

                            // Add event listener to the editable element
                            $('#<?= $sjid ?>').on('input', function() {
                                // Clear the previous timeout
                                clearTimeout(delay);

                                // Set a new timeout to delay the AJAX request
                                delay = setTimeout(function() {
                                    var description = $(this).text(); // Get the updated description
                                    var sjid = $(this).attr('id'); // Get the ID

                                    // Send an AJAX request to update the description
                                    $.ajax({
                                        url: 'update_description.php', // Path to your PHP script for updating the description
                                        method: 'POST',
                                        data: {
                                            sjid: sjid,
                                            description: description
                                        },
                                        success: function(response) {
                                            // Handle the response if needed
                                            console.log('Description updated successfully');
                                        },
                                        error: function(xhr, status, error) {
                                            // Handle any errors
                                            console.error(error);
                                        }
                                    });
                                }.bind(this), 1000); // Adjust the delay time as needed
                            });
                        });
                    </script>

                    <tr class=" w3-border-bottom w3-border-black">
                        <th class="w3-right-align">Jumlah: </th>
                        <td><?= $sjData['qty'] ?></td>
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
                <select name="printType">
                    <option value="LX" <?= $printLXSelected ?>>LX Printer</option>
                    <option value="NORMAL"<?= $printNormalSelected ?>>Normal Printer</option>
                </select>
                <button type="submit">Pilih Format</button>
            </form>


            <button class="w3-button w3-blue-grey w3-padding w3-bar" onclick="printPage('normal')">
                Print <?= $printType ?> &nbsp;
                <i class="fas fa-print"></i>
            </button>
        </div>

    </div>

</body>

<script>
    function printPage() {
        // Get the value of the 'sjid' input field (or any other method you use to get the value)
        var sjid = '<?= $sjid ?>';

        // Execute the PHP function after the print is completed
        // This will trigger a request to the print_success.php file, passing the 'sjid' variable as a POST parameter.
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'print_success.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Handle the response here (if needed)
                console.log(xhr.responseText);
            }
        };
        xhr.send('sjid=' + encodeURIComponent(sjid));

        window.print()
    }
</script>



</html>
