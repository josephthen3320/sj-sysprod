<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
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

<div class="w3-bar w3-black">
    <h5 class="w3-bar-item">Selected Article</h5>
</div>

<div class="w3-container">
    <?php
        include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_articles.php";

        if (!isset($_GET['id'])) {
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $id = isset($_GET['id']) ? $_GET['id'] : '';
            $a = getArticleById($id);
        }

        $description = $a['description'];
        if ($description == null || $description == "") {
            $description = "-";
        }
    ?>

    <div class="w3-rest w3-center">
            <h3 class="w3-bar-item"><?= $a['model_name'] ?></h3>
        <h6><?= $a['article_id'] ?></h6>

        <?php
        // Read the source WebP image
        $sourceImagePath = $_SERVER['DOCUMENT_ROOT'] . '/img/articles/' . $a['sample_img_path'];
        $convertedImagePath = $_SERVER['DOCUMENT_ROOT'] . '/img/articles/temp_converted.jpg';

        // Check if the 'imagecreatefromwebp' function exists (requires the WebP support library)
        if (!function_exists('imagecreatefromwebp')) {
            echo "WebP support not available.";
            exit;
        }

        // Load the WebP image
        $image = imagecreatefromwebp($sourceImagePath);

        // Create a blank image with JPG format
        $convertedImage = imagecreatetruecolor(imagesx($image), imagesy($image));
        $white = imagecolorallocate($convertedImage, 255, 255, 255);
        imagefill($convertedImage, 0, 0, $white);

        // Copy the WebP image onto the blank image to convert
        imagecopy($convertedImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));

        // Save the converted image as JPEG
        imagejpeg($convertedImage, $convertedImagePath, 100); // 100 is the image quality

        // Capture the contents of the converted image as base64
        $base64Image = base64_encode(file_get_contents($convertedImagePath));

        // Clean up by destroying the images and deleting the temporary image
        imagedestroy($image);
        imagedestroy($convertedImage);
        unlink($convertedImagePath);

        // Echo the base64-encoded image data
        //echo $base64Image;

        $jpg64Path = 'data:image/jpeg;base64,' . $base64Image;
        ?>

        <img src='/img/articles/<?= $a['sample_img_path'] ?>' width='70%' style="cursor: pointer"
             ondblclick="openPopupURLImageJpg('imagePopup')">

        <h6 class="w3-small"><i class="fas fa-fw fa-magnifying-glass"></i> &nbsp; Klik 2x untuk memperbesar </h6>



        <script>
            function openPopupURLImageJpg(name) {
                // Construct an image data URL
                var imageDataURL = 'data:image/jpeg;base64,' + '<?= $base64Image ?>';

                // Create a popup window with the image
                var popup = window.open(" ", name, "width=" + '650' + ",height=" + "840" + ",top=100,left=200,resizable=no,scrollbars=no,toolbar=no,menubar=no,status=no");
                popup.document.write('<img src="' + imageDataURL + '" width="640">');
            }
        </script>
    </div>

    <style>
        span b {
            display: inline-block;
            width: 100px;
        }
    </style>

    <div class="w3-padding-16" style="margin-bottom: 64px;">
        <span><b>Brand:</b> <?= getBrandNameById($a['brand_id']) ?></span><br>
        <span><b>Embro:</b> <?= getCMTNameById($a['embro_cmt_id']) ?></span><br>
        <span><b>Print/Sablon:</b> <?= getCMTNameById($a['print_cmt_id']) ?></span><br>
        <span><b>Rib:</b> <?= $a['is_rib'] ? "YES" : "NO" ?></span><br>
        <span><b>Sample Code:</b> <?= $a['sample_code'] ?></span><br>
        <span><b>Wash:</b> <?= implode(", ", fetchWashNamesByArticleId($a['article_id'])) ?></span><br>
        <span>
            <b>Keterangan:</b>
            <?= $description ?>
        </span>
    </div>

    <?php if($_SESSION['user_role'] <= 1): ?>
    <button class="w3-button w3-bar w3-red w3-bottom" onclick="reloadPage()"><i class="fa-solid fa-sync"></i> Reload Frame</button>
    <?php endif; ?>

    <script>
        function reloadPage() {
            location.reload();
        }
    </script>

</div>


</body>
</html>