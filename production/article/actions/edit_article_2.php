<?php
session_start();


include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_classification.php';

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $articleId = $_GET['aid'];
    $articleData = getArticleById($articleId);

}
?>

<form action="" method="post">

    <input value="<?= $articleId ?>">
    <input value="<?= $articleData['model_name'] ?>">

    <select name="embroCMTId">
        <?php
        $embroCMT = fetchAllCMTByType("CT2");

        while ($embro = $embroCMT->fetch_assoc()) :
            $embroSelected = $sablonSelected = "";
            if ($articleData['embro_cmt_id'] == $embro['cmt_id']) {
                $embroSelected = "selected";
            }
        ?>
        <option value="<?= $embro['cmt_id'] ?>" <?= $embroSelected ?>><?= $embro['cmt_name'] ?></option>

        <?php endwhile; ?>
    </select>

    <select name="printCMTId">
        <?php
        $sablonCMT = fetchAllCMTByType("CT2");

        while ($sablon = $sablonCMT->fetch_assoc()) :
            $sablonSelected = $sablonSelected = "";
            if ($articleData['print_cmt_id'] == $sablon['cmt_id']) {
                $sablonSelected = "selected";
            }
        ?>
        <option value="<?= $sablon['cmt_id'] ?>" <?= $sablonSelected ?>><?= $sablon['cmt_name'] ?></option>

        <?php endwhile; ?>
    </select>


</form>
