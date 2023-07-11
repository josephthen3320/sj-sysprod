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
    <table class="w3-table w3-table-all">
        <thead>
        <tr>
            <th>No</th>
            <th>Article ID.</th>
            <th>Sample Image</th>
            <th>Item</th>
            <th>Category</th>
            <th>Subcategory</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
            include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';

            $articles = fetchAllArticles();
            $i = 0;
            foreach($articles as $a) {
                ++$i;

                $category = getCategoryNameById($a['category_id']);
                $subcategory = getSubcategoryNameById($a['subcategory_id']);

                echo "<tr class='w3-hover-blue-grey' onclick='loadArticleDetail(\"{$a['article_id']}\")' style='cursor: pointer;'>";
                echo "<td>$i</td>";
                echo "<td>{$a['article_id']}</td>";
                echo "<td><img src='/img/articles/{$a['sample_img_path']}' width='120px'></td>";
                echo "<td>{$a['model_name']}</td>";
                echo "<td>{$category}</td>";
                echo "<td>{$subcategory}</td>";
                echo "<td>";
                //        <button class='w3-button w3-green' onclick='loadArticleDetail(\"{$a['article_id']}\")'><i class='fa-solid fa-info-circle'></i></button>
                //    ";
                if (false /*todo: add role check here*/) {
                    echo "
                    <button class='w3-button w3-blue' ><i class='fa-solid fa-pencil'></i ></button >
                    <button class='w3-button w3-red' ><i class='fa-solid fa-trash'></i ></button >
                    ";
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