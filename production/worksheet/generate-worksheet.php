<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnProduction();

$title = "Generate Worksheet";

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'; // include_once PHPExcel library
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $order              = $_GET['o']    ?? "";
    $fabric_utama       = $_GET['fu']   ?? "";
    $cloth              = $_GET['cw']   ?? "";
    $general_est_cons   = $_GET['gec']  ?? "";
    $embro              = $_GET['e']    ?? "";
    $print              = $_GET['p']    ?? "";

    $ws_type            = $_GET['wstype'] == "i" ? "internal" : "external";


    $worksheet_detail_sql = "SELECT * FROM worksheet_detail WHERE id = $id";
    $worksheet_detail_result = $conn->query($worksheet_detail_sql);

    if ($worksheet_detail_result->num_rows > 0) {
        $wd = $worksheet_detail_result->fetch_assoc();

        // Process the worksheet details
        $worksheet_id       = $wd['worksheet_id'];
        $ws = fetchWorksheet($worksheet_id);

        $worksheet_date     = $ws['worksheet_date'];
        $delivery_date      = DateTime::createFromFormat('Y-m-d', $ws['delivery_date'])->format('M Y');
        $po_date            = $ws['po_date'];


        $article_id         = $wd['article_id'];
        $qty                = $wd['qty'];
        $customer_id        = $wd['customer_id'];
        $cloth_width        = $wd['cloth_width'];
        $is_fob             = isset($wd['is_fob']) == 1 ? "YES" : "NO";

        $art_name           = $wd['art_name'];
        $art_brand          = $wd['art_brand'];
        $art_cmt_embro      = $wd['art_cmt_embro'];
        $art_cmt_print      = $wd['art_cmt_print'];
        $art_rib            = $wd['art_rib'];
        $art_sample_code    = $wd['art_sample_code'];


        $category_name = fetchCategoryName(fetchArticle($article_id)['category_id']);
        $subcategory_name = fetchSubCategoryName(fetchArticle($article_id)['subcategory_id']);

        $sql = "SELECT sample_img_path FROM article WHERE article_id = '$article_id' ";
        $result = mysqli_query($conn, $sql);
        $image_filename = mysqli_fetch_assoc($result)['sample_img_path'];


        // Load the template worksheet
        $templatePath = 'templates/SPK_MASTER.xlsx';
        $spreadsheet = IOFactory::load($templatePath);

        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();

        /* Image Processing */
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/img/articles/' . $image_filename;

        // Load the .webp image
        $webpImage = imagecreatefromwebp($imagePath);

        if ($webpImage !== false) {
            // Path for the converted .jpg image
            $jpgImagePath = $_SERVER['DOCUMENT_ROOT'] . '/img/articles/temp/'. pathinfo($image_filename, PATHINFO_FILENAME) . '_' . date('YmdHis') .'.jpg';

            // Convert the image to .jpg format and save it
            imagejpeg($webpImage, $jpgImagePath, 100);

            $drawing = new Drawing();
            $drawing->setName('Image');
            $drawing->setDescription('Image Description');
            $drawing->setPath($jpgImagePath);
            $drawing->setCoordinates('B15');
            $drawing->setWidthAndHeight(500, 500);
            $drawing->setOffsetX(50);  // Adjust the X offset value here
            $drawing->setWorksheet($sheet);

            // Get the contents of the converted .jpg image into a variable
            // $jpeg_image = file_get_contents($jpgImagePath);

            // Delete the temporary .jpg file


            // Free up memory
            imagedestroy($webpImage);
        } else {
            // Error handling if the image cannot be loaded
            echo 'Failed to load the .webp image.';
        }

        /*
        $drawing = new Drawing();
        $drawing->setName('Image');
        $drawing->setDescription('Image Description');
        $drawing->setPath($imagePath);
        $drawing->setCoordinates('B15');
        $drawing->setWidthAndHeight(500, 500);
        $drawing->setOffsetX(50);  // Adjust the X offset value here
        $drawing->setWorksheet($sheet);
        */

        // Fill in the values from the form
        $sheet->setCellValue('B2', $category_name);                         // Category
        $sheet->setCellValue('B4', $subcategory_name);                      // Subcategory

        $sheet->setCellValue('C6', $worksheet_date);                        // WS Date
        $sheet->setCellValue('J6', ('No. WKS : ' . $worksheet_id));         // No. WS
        $sheet->setCellValue('C7', $article_id);                            // Artikel
        $sheet->setCellValue('C8', $art_name);                              // Model Artikel
        $sheet->setCellValue('C9', $qty);                                   // QTY



        $sheet->setCellValue('C10', $delivery_date);                        // Delivery date
        $sheet->setCellValue('C11', $wd['art_brand']);                      // Merk
        $sheet->setCellValue('F10', $wd['cloth_width']);                    // Merk
        $sheet->setCellValue('F11', $wd['art_rib']);                        // Rib

        $sheet->setCellValue('I7', $wd['art_cmt_embro']);                   // Embro
        $sheet->setCellValue('I8', $wd['art_cmt_print']);                   // Print


        $w_names = fetchWashNames($article_id);
        $w_startRow = 9;
        foreach ($w_names as $index => $wash) {
            $row = $w_startRow + $index;
            $sheet->setCellValue('I' . $row, $wash);                        // Wash type (assuming possible to have >1
        }

        // $sheet->setCellValue('C7', $article_id);                 // Artikel
        // $sheet->setCellValue('C8', $article_id);                 // Model
        // $sheet->setCellValue('C9', $article_id);                 // Estimasi Qty
        // $sheet->setCellValue('C10', $article_id);                // Delivery
        // $sheet->setCellValue('C11', $article_id);                // Merk

        $sheet->setCellValue('F7', $order);                         // Order
        $sheet->setCellValue('F8', $fabric_utama);                  // Fabric utama
        // $sheet->setCellValue('F9', $article_id);                 // Repeat
        $sheet->setCellValue('F10', $cloth);                        // Lebar Kain
        // $sheet->setCellValue('F11', $article_id);                // Rib

        if ($embro != "") {
            $sheet->setCellValue('I7', $embro);                     // Embro
        }

        if ($print != "") {
            $sheet->setCellValue('I8', $print);                     // Print
        }

        // $sheet->setCellValue('I9', $article_id);                 // Wash

        // $sheet->setCellValue('L7', $article_id);                 // CMT
        $sheet->setCellValue('L8', $general_est_cons);              // General Est. Cons



        // Save the generated worksheet
        $date = date('Ymd');
        $outputPath = "generated_worksheet/SPK_{$worksheet_id}_{$date}_{$ws_type}.xlsx";
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($outputPath);

        // Free up image temp memory
        unlink($jpgImagePath);
        // echo 'Worksheet generated successfully. <a href="' . $outputPath . '">Download</a>';


    } else {
        echo "No worksheet details found for the provided ID.";
    }

    //$worksheet_detail_stmt->close();
}


?>


<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title . ": " . $ws['worksheet_id'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
    <style>
        body {
            /*background-color: #F4EDE9;*/
        }
    </style>

    <script src="/assets/js/popup-timeout.js"></script>
</head>

<body>
<div class="w3-top w3-bar w3-blue-gray">
    <span class="w3-bar-item"><?= $title ?></span>
</div>

<div class="w3-container w3-padding-64">

    <h3><?=$worksheet_id?> | <?= strtoupper($ws_type) ?></h3>

    <h5>Confirm worksheet details:</h5>

    <div class="w3-row">
        <div class="w3-half w3-padding">
            <label>Worksheet ID</label>
            <input class="w3-input w3-border" type="text" readonly value="<?= $worksheet_id ?>">

            <label>Delivery Date (planned)</label>
            <input class="w3-input w3-border" type="text" readonly value="<?= $delivery_date ?>">
        </div>
        <div class="w3-half w3-padding">
            <label>Worksheet Date</label>
            <input class="w3-input w3-border" type="text" readonly value="<?= $worksheet_date ?>">

            <label>PO Date</label>
            <input class="w3-input w3-border" type="text" readonly value="<?= isset($po_date) && $po_date != "0000-00-00" ? $po_date : "Undefined" ?>">

            <label>Article ID</label>
            <input class="w3-input w3-border" type="text" readonly value="<?= $article_id ?>">
        </div>
    </div>

</div>

<div class="w3-bar w3-blue-grey w3-bottom" style="">
    <div class="w3-container w3-bar-item">
        <span class="w3-bar-item"><?= $outputPath ?></span>
    </div>
    <div class="w3-container w3-bar-item">
        <span class="w3-bar-item"><?= "" ?></span>
    </div>
    <div class="w3-container w3-bar-item w3-right" style="" id="wsDownload">
        <a class="w3-button w3-red" href="<?= $outputPath ?>" id="wsDownloadLink" onclick="disableLink()">Download &nbsp;&nbsp; <i class="fa-solid fa-file-arrow-down"></i></a>
    </div>
</div>

</body>
</html>

<script>

    // Function to disable the link and add the disabled class to the container
    function disableLink() {
        var container = document.getElementById("wsDownload");
        var link = document.getElementById("wsDownloadLink");


        // Add the w3-disabled class to the container
        container.classList.add("w3-disabled");
        link.classList.add("w3-green");
        link.classList.remove("w3-red");

        link.innerHTML = "Downloaded!";

        // Re-enable the link after 5 seconds
        setTimeout(function() {
            link.innerHTML = "Download Again &nbsp; &nbsp;<i class=\"fa-solid fa-file-arrow-down\"></i>";
            link.removeAttribute("disabled");
            container.classList.remove("w3-disabled");
        }, 10000);
    }
</script>

<?php
function fetchWashNames($article_id) {
    include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT wash_id FROM article_wash WHERE article_id = '$article_id'";
    $result = $conn->query($sql);

    $wash_names = array();

    // Check if the query was successful
    if ($result) {
        // Fetch all the rows
        $wash_ids = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $wash_ids[] = $row['wash_id'];
        }

        // Output the results
        foreach ($wash_ids as $w_id) {
            $sql = "SELECT wash_type_name FROM wash_type WHERE wash_type_id = '$w_id'";
            $result = $conn->query($sql);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $wash_names[] = $row['wash_type_name'];
                }
            }
        }
    }

    return $wash_names;

}


function fetchCategoryName($cat_id) {

    include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT category_name FROM main_category WHERE category_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $cat_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0 ){
        $c_name = $result->fetch_assoc();
        return $c_name['category_name'];
    }

    return 1;
}

function fetchSubCategoryName($subcat_id) {

    include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT subcategory_name FROM subcategory WHERE subcategory_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $subcat_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0 ){
        $c_name = $result->fetch_assoc();
        return $c_name['subcategory_name'];
    }

    return 1;
}


function fetchArticle($art_id) {
    include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT * FROM article WHERE article_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $art_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0 ){
        $article = $result->fetch_assoc();
        return $article;
    }

    return 1;
}


function fetchWorksheet($w_id) {
    include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $w_sql = "SELECT * FROM worksheet WHERE worksheet_id = '$w_id'";
    $w_result = $conn->query($w_sql);

    /* todo: look into why this fucks up
    $w_stmt = $conn->prepare($w_sql);
    $w_stmt->bind_param('i', $w_id);
    $w_stmt->execute();
    $w_result = $w_stmt->get_result();
    */

    if ($w_result->num_rows > 0) {
        $ws = $w_result->fetch_assoc();

        return $ws;

    } else {
        echo "No worksheet details found for the provided ID.";
    }

    $conn->close();
    return 1;
}

?>