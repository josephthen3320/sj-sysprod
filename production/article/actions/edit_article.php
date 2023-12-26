<?php

session_start();

if (!in_array($_SESSION['user_role'], [0,1,2,5,6])) {
    echo "Forbidden";
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_classification.php';


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getConnProduction();

    $internalId = $_POST['internalId'];
    $originalId = $_POST['originalArticleId'];
    $articleId = $_POST['articleId'];
    $modelName = $_POST['modelName'];
    $sampleCode = $_POST['sampleCode'];
    $brandId = $_POST['brandId'];
    $embroCMTId = $_POST['embroCMTId'];
    $printCMTId = $_POST['printCMTId'];
    $isRib = isset($_POST['isRib']) ? true : false;

    $sql = "UPDATE article 
            SET 
                article_id = '$articleId',
                model_name = '$modelName', 
                sample_code = '$sampleCode', 
                brand_id = '$brandId', 
                embro_cmt_id = '$embroCMTId', 
                print_cmt_id = '$printCMTId', 
                is_rib = '$isRib' 
            WHERE id = '$internalId'";

    $conn->query($sql);

    $sql = "UPDATE article_wash
            SET
              article_id = '$articleId'
            WHERE 
              article_id = '$originalId'";


    $conn->query($sql);
    $conn->close();

    echo $closeWindowScript = "<script type='text/javascript'>window.close();</script>";
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

<div class="w3-bar w3-black">
    <h5 class="w3-bar-item">Edit Article</h5>
</div>

<div class="w3-container">
    <?php

    if (!isset($_GET['aid'])) {
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $id = isset($_GET['aid']) ? $_GET['aid'] : '';
        $a = getArticleById($id);

        $conn = getConnProduction();
        $sql = "SELECT id FROM article WHERE article_id = '$id'";

        $internalId = $conn->query($sql)->fetch_assoc()['id'];
        $originalId = $id;
    }
    ?>

    <div class="w3-rest w3-center w3-padding-top-24">
        <img src='/img/articles/<?= $a['sample_img_path'] ?>' width='35%'>
    </div>

    <style>
        span b {
            display: inline-block;
            width: 100px;
        }
    </style>

    <form action="" method="post">
    <div class="w3-padding-16" style="margin-bottom: 64px;">
        <input hidden value="<?= $internalId ?>" name="internalId">
        <input hidden value="<?= $originalId ?>" name="originalArticleId">


        <span><b>No. Article:</b></span>
        <input class='w3-input w3-border w3-border-grey'value="<?= $id ?>" name="articleId">
        <br>

        <span><b>Nama Model:</b></span>
        <input class='w3-input w3-border w3-border-grey'value="<?= $a['model_name'] ?>" name="modelName">
        <br>

        <span><b>Kode Sample:</b></span>
        <input class='w3-input w3-border w3-border-grey'value="<?= $a['sample_code'] ?>" name="sampleCode">
        <br>

        <span><b>Brand:</b></span>
        <select class='w3-select w3-border w3-border-grey' name="brandId">
            <?php
            $brands = fetchBrands();

            while ($brand = $brands->fetch_assoc()) :
                $brandSelected = $brandSelected = "";
                if ($a['brand_id'] == $brand['brand_id']) {
                    $brandSelected = "selected";
                }
                ?>
                <option value="<?= $brand['brand_id'] ?>" <?= $brandSelected ?>><?= $brand['brand_name'] ?></option>

            <?php endwhile; ?>
        </select>
        <br>
        <br>

        <span><b>Embro:</b></span>
        <select class='w3-select w3-border w3-border-grey' name="embroCMTId">
            <?php
            $embroCMT = fetchAllCMTByType("CT2");

            while ($embro = $embroCMT->fetch_assoc()) :
                $embroSelected = $sablonSelected = "";
                if ($a['embro_cmt_id'] == $embro['cmt_id']) {
                    $embroSelected = "selected";
                }
                ?>
                <option value="<?= $embro['cmt_id'] ?>" <?= $embroSelected ?>><?= $embro['cmt_name'] ?></option>

            <?php endwhile; ?>
        </select>
        <br>
        <br>

        <span><b>Print/Sablon:</b></span>
        <select class='w3-select w3-border w3-border-grey' name="printCMTId">
            <?php
            $sablonCMT = fetchAllCMTByType("CT2");

            while ($sablon = $sablonCMT->fetch_assoc()) :
                $sablonSelected = $sablonSelected = "";
                if ($a['print_cmt_id'] == $sablon['cmt_id']) {
                    $sablonSelected = "selected";
                }
                ?>
                <option value="<?= $sablon['cmt_id'] ?>" <?= $sablonSelected ?>><?= $sablon['cmt_name'] ?></option>

            <?php endwhile; ?>
        </select>
        <br>
        <br>

        <span><b>Rib:</b></span>
        <?php $isRib = $a['is_rib'] ? "checked" : "" ?>
        <input class='w3-check w3-border w3-border-grey' type="checkbox" <?= $isRib?> name="isRib">
        <br>
        <br>


        <span><b>Wash:</b> <?= implode(", ", fetchWashNamesByArticleId($a['article_id'])) ?></span><br>
        <span class="w3-small w3-text-red">* Hubungi IT untuk mengubah Wash data.</span>
        <br><br>
        <button type="submit" class="w3-button w3-blue w3-bar">Save</button>
    </div>
    </form>


</div>


</body>
</html>