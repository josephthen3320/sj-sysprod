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

<body>

<div class="w3-container classification-content" id="subcategory_modal" style="">
    <h3>Subcategory</h3>
    <?php
    $subcategories      = include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/fetch/master-data/fetch-subcategory.php";
    $categories         = include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/fetch/master-data/fetch-category.php";
    ?>

    <!-- Buttons Container -->
    <div class="w3-bar" style="margin-bottom: 12px;">
        <button class="w3-button w3-green w3-bar-item" onclick="openPopup('action/add-subcategory.php', 'class-add')" style="margin-right: 16px;">
            <i class="fa-solid fa-plus-circle"></i> Add Subcategory
        </button>
        <select class="w3-bar-item w3-select w3-quarter" id="categorySelect" onchange="filterSubcategories()">
            <option disabled value="" selected hidden>Filter by Category</option>
            <option value="">All Categories</option>
            <?php foreach ($categories as $category) : ?>
                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_id'] . "&nbsp; | &nbsp;" . $category['category_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="subcategoryTableContainer">
        <table class="w3-table w3-table-all">
            <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            foreach ($subcategories as $subcategory) :
                $subcat_id = $subcategory['id'];
                $edit_url = "action/edit-subcategory.php?id={$subcat_id}";
                $delete_url = "action/delete-subcategory.php?id={$subcat_id}";

                echo "<tr>";
                echo "<td>" . (++$i) . "</td>";
                echo "<td>{$subcategory['subcategory_id']}</td>";
                echo "<td>{$subcategory['subcategory_name']}</td>";
                echo "<td>
                            <button class='w3-button w3-round w3-blue' onclick='openPopup(\"{$edit_url}\", \"action\")'>
                                <i class='fa-solid fa-pen'></i>&nbsp;&nbsp;Edit
                            </button>
                            <button class='w3-button w3-round w3-red' onclick='openPopup(\"{$delete_url}\", \"action\")'>
                                <i class='fa-solid fa-trash'></i>
                            </button>
                          </td>";
                echo "</tr>";
            endforeach;
            ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function filterSubcategories() {
        var selectedCategory = document.getElementById('categorySelect').value;

        // Send an AJAX request to filter-subcategory.php with the selected category_id
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                document.getElementById('subcategoryTableContainer').innerHTML = this.responseText;
            }
        };
        xhttp.open('GET', 'php/filter-subcategory.php?category_id=' + selectedCategory, true);
        xhttp.send();
    }
</script>

</body>
</html>