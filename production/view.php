<?php
session_start();

$root = $_SERVER['DOCUMENT_ROOT'];
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";


require_once $root . "/php-modules/db.php";
$conn = getConnUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Articles</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">

    <style>
        body {
            background-color: #F4EDE9;
        }
        .w3-top {
            margin-bottom: 60px; /* Add margin-bottom to push the top bar down */
        }

        .w3-sidebar {
            top: 50px; /* Add top positioning to the left bar */
        }
    </style>
</head>
<body>

<!-- Top Bar -->
<?php require_once $_SERVER['DOCUMENT_ROOT']. "/modular/nav-topbar.php"; ?>

<!-- Left Bar -->
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/modular/nav-sidebar.php"; ?>


<div class="w3-main w3-padding-64 w3-container" style="margin-left: 220px;">

    <h1>View Articles</h1>

    <?php
    $articles = include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/fetch/fetch-articles.php';

    /*
    foreach ($articles as $article) {
        print $article['id'] . " | " . $article['article_id'] . "<br>";
    }*/
    ?>

    <table class="w3-table w3-table-all">
        <thead>
        <tr class="w3-blue-gray">
            <th>No</th>
            <th>Article ID</th>
            <th>Sample Image</th>
            <th>Item</th>
            <th>Category</th>
            <th>Subcategory</th>
            <th>Actions</th>
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


            echo "<tr>";
            echo "<td>" . $i++ . "</td>";
            echo "<td>" . $article['article_id'] . "</td>";
            echo "<td><img src='/img/articles/{$article['sample_img_path']}' width='120px'></td>";
            echo "<td>" . $article['model_name'] . "</td>";
            echo "<td>" . $category_name . "</td>";
            echo "<td>" . $subcategory_name . "</td>";

            echo "<td>
                    <button onclick='openPopup(\"detail.php?id={$article['id']}\", \"detailArticle\", )' class='w3-button w3-round-large w3-green'><i class=\"fa-solid fa-circle-info\"></i></button>
                    <button onclick='openPopup(\"edit.php?id={$article['id']}\", \"editArticle\")' class='w3-button w3-round-large w3-blue'><i class=\"fa-solid fa-pen-to-square\"></i></button>
                    <a href='delete.php?id={$article['id']}' class='w3-button w3-round-large w3-red'><i class=\"fa-solid fa-trash\"></i></a>
                </td>
                ";
            echo "</tr>";
        endforeach;
        ?>
        </tbody>
    </table>

    <script>
        function openPopup(url, name) {
            var windowFeatures = "width=840,height=500,resizable=no,scrollbars=no,toolbar=no,menubar=no,location=no,status=no";

            window.open(url, name, windowFeatures);
        }
    </script>

</div>





<!--footer class="w3-bottom w3-bar w3-center w3-padding-16 w3-tiny" style="background-color: #222222;">
    &copy; 2023 CV Subur Jaya. All rights reserved.
</footer-->




</body>
</html>