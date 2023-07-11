<?php
$articles = include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/fetch/fetch-articles.php';
?>
<html>

<head>
    <title>Select Article</title>
    <link rel="stylesheet" href="/assets/css/w3.css">
</head>

<body>

<table class="w3-table w3-table-all">
    <thead>
    <tr class="w3-blue-gray">
        <th>No</th>
        <th>Article ID</th>
        <th>Sample Image</th>
        <th>Item</th>
        <th>Category</th>
        <th>Subcategory</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    foreach ($articles as $article):
        $category_id = $article['category_id'];
        $subcategory_id = $article['subcategory_id'];

        // Fetch Category Name
        $category_query = "SELECT category_name FROM main_category WHERE category_id = '$category_id'";
        $result = $conn->query($category_query);
        $category_name = ($result->fetch_assoc())['category_name'];

        // Fetch Subcategory name
        $subcategory_query = "SELECT subcategory_name FROM subcategory WHERE subcategory_id = '$subcategory_id'";
        $result = $conn->query($subcategory_query);
        $subcategory_name = ($result->fetch_assoc())['subcategory_name'];

        echo "<tr class='w3-hover-grey' onclick='window.opener.selectArticle(\"" . $article['article_id'] . "\"); window.close();' style='cursor: pointer;'>";
        echo "<td>" . $i++ . "</td>";
        echo "<td>" . $article['article_id'] . "</td>";
        echo "<td><img src='/img/articles/{$article['sample_img_path']}' height='100px' /></td>";
        echo "<td>" . $article['model_name'] . "</td>";
        echo "<td>" . $category_name . "</td>";
        echo "<td>" . $subcategory_name . "</td>";
        echo "</tr>";
    endforeach;
    ?>
    </tbody>
</table>

</body>
</html>