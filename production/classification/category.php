<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
    <script src="/assets/js/utils.js"></script>
</head>

<body>

<div class="w3-container classification-content" id="category_modal">
    <h3>Main Category</h3>
    <?php $categories = include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/fetch/master-data/fetch-category.php"; ?>
    <!-- Todo: create & edit & view -->

    <!-- Buttons Container -->
    <div class="w3-bar" style="margin-bottom: 12px;">
        <button class="w3-bar-item w3-button w3-green" onclick="openPopupURL2('action/add-category.php', 'class-add')">
            <i class="fa-solid fa-plus-circle"></i>
            Add Category
        </button>
    </div>

    <!-- Table View -->
    <table class="w3-table w3-table-all">
        <thead>
        <tr>
            <th>No</th>
            <th>ID</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $i = 0;
        foreach ($categories as $category) :
            $cat_id = $category['id'];
            $edit_url = "action/edit-category.php?id={$cat_id}";
            $delete_url = "action/delete-category.php?id={$cat_id}";

            echo "<tr>";
            echo "<td>" . ++$i . "</td>";
            echo "<td>{$category['category_id']}</td>";
            echo "<td>{$category['category_name']}</td>";
            echo "<td>
                            <button class='w3-button w3-round w3-blue' onclick='openPopup(\"" . $edit_url . "\", \"action\")'>
                                <i class=\"fa-solid fa-pen\"></i>&nbsp;&nbsp;Edit
                            </button>
                            <button class='w3-button w3-round w3-red' onclick='openPopup(\"" . $delete_url . "\", \"action\")'>
                                <i class=\"fa-solid fa-trash\"></i>
                            </button>
                          </td>";
            echo "<tr>";
        endforeach;
        ?>

        </tbody>
    </table>
</div>

</body>
</html>