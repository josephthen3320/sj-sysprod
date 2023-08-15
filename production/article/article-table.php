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
<div class="w3-container classification-content" id="worksheet-modal" style="">
    <table class="w3-table w3-table-all w3-small">
        <thead>
        <tr>
            <th class="w3-center" style="width: 5%;">No</th>
            <th class="w3-center" style="width: 10%;">Article ID</th>
            <th class="w3-center" style="width: 15%;">Sample Image</th>
            <th class="w3-left-align" style="width: 20%;">Item</th>
            <th class="w3-center" style="width: 15%;">Category</th>
            <th class="w3-center" style="width: 15%;">Subcategory</th>
            <th class="w3-center" style="width: 20%;">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
            session_start();
            include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';

            $articles = fetchAllArticles();
            $i = 0;
            foreach($articles as $a) {
                ++$i;

                $category = getCategoryNameById($a['category_id']);
                $subcategory = getSubcategoryNameById($a['subcategory_id']);

                $modelName = strtoupper($a['model_name']);

                echo "<tr class='w3-hover-blue-grey' onclick='loadArticleDetail(\"{$a['article_id']}\")' style='cursor: pointer;'>";
                echo "<td class='w3-center' style='vertical-align: middle;'>$i</td>";
                echo "<td class='w3-center' style='vertical-align: middle;'>{$a['article_id']}</td>";
                echo "<td class='w3-center' style='vertical-align: middle;'><img src='/img/articles/{$a['sample_img_path']}' style='width: 80px; height: 80px; object-fit: cover;' /></td>";
                echo "<td class='w3-left-align' style='vertical-align: middle;'>{$modelName}</td>";
                echo "<td class='w3-center' style='vertical-align: middle;'>{$category}</td>";
                echo "<td class='w3-center' style='vertical-align: middle;'>{$subcategory}</td>";
                echo "<td class='w3-center' style='vertical-align: middle;'>";
                //        <button class='w3-button w3-green' onclick='loadArticleDetail(\"{$a['article_id']}\")'><i class='fa-solid fa-info-circle'></i></button>
                //    ";

                if (in_array($_SESSION['user_role'], [0,1,2,5,6]) && !checkIfArticleAlreadyUsed($a['article_id'])) { /*todo: add role check here*/
                    $editURL = "actions/edit_article.php?aid=" . $a['article_id'];
                    echo "
                    <button class='w3-button w3-blue' onclick='openPopupURL2(\"{$editURL}\")'><i class='fa-solid fa-pencil'></i></button >
                    ";
                    /* <button class='w3-button w3-red' ><i class='fa-solid fa-trash'></i></button > */
                    }
                echo "</td>";
                echo "</tr>";
            }

        ?>
        </tbody>
    </table>
</div>

<script>
    function loadArticleDetail(id) {
        // Send message to the parent window
        window.parent.postMessage({ type: "loadArticleDetail", id: id }, "*");
    }
</script>

</body>
</html>

<?php

function checkIfArticleAlreadyUsed($article_id) {
    $conn = getConnProduction();
    $sql = "SELECT * FROM worksheet_detail WHERE article_id = '$article_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return 1;
    } else {
        return 0;
    }


}

?>