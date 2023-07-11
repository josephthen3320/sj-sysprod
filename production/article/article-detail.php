<?php
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
    ?>

    <div class="w3-rest w3-center">
            <h3 class="w3-bar-item"><?= $a['model_name'] ?></h3>
        <h6><?= $a['article_id'] ?></h6>
        <img src='/img/articles/<?= $a['sample_img_path'] ?>' width='70%'>
    </div>

    <style>
        span b {
            display: inline-block;
            width: 100px;
        }
    </style>

    <div class="w3-padding-16">
        <span><b>Brand:</b> <?= getBrandNameById($a['brand_id']) ?></span><br>
        <span><b>CMT:</b> <?= getCMTNameById($a['embro_cmt_id']) ?></span><br>
        <span><b>CMT:</b> <?= getCMTNameById($a['print_cmt_id']) ?></span><br>
        <span><b>Rib:</b> <?= $a['is_rib'] ? "YES" : "NO" ?></span><br>
        <span><b>Sample Code:</b> <?= $a['sample_code'] ?></span><br>
        <span><b>Wash:</b> <?= implode(", ", fetchWashNamesByArticleId($a['article_id'])) ?></span>
    </div>

    <button class="w3-button w3-bar w3-red w3-bottom" onclick="reloadPage()"><i class="fa-solid fa-sync"></i> Reload Frame</button>

    <script>
        function reloadPage() {
            location.reload();
        }
    </script>

</div>


</body>
</html>