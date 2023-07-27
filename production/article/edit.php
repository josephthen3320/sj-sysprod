<?php
session_start();

$page_title = "Create Article";

// Check if the user is not logged in, redirect to login page
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/agents/logging.php";

$message = "";
if (isset($_SESSION['msg'])) {
    $message = $_SESSION['msg'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Article</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>

<body class="w3-container">


<?php    // Include the database connection file
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
$conn = getConnProduction();
?>

<h1>Create Article</h1>
<?= $message; unset($_SESSION['msg']); ?>

<form class="" action="actions/edit_article.php" method="POST" enctype="multipart/form-data">

    <!-- Left column 50% -->
    <div class="w3-half">
        <!-- Article Name -->
        <div class="w3-container w3-margin-bottom">
            <div class="w3-padding">
                <label for="model_name">Nama Artikel:</label>
                <input class="w3-input" type="text" name="model_name" id="model_name" placeholder="Baju Kemeja Oversize" required>
            </div>
        </div>

        <div class="w3-container">
            <!-- Subcategory -->
            <div class="w3-half w3-padding">
                <label for="subcategory_id">Subcategory:</label>
                <select class="w3-select" name="subcategory_id" id="subcategory_id" required>
                    <option value="" disabled selected>Please select</option>
                    <?php
                    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';

                    // Retrieve subcategory data from the subcategory table
                    $subcategoryQuery = "SELECT subcategory_id, subcategory_name, category_id FROM subcategory ORDER BY category_id ASC";
                    $subcategoryResult = mysqli_query($conn, $subcategoryQuery);

                    $lastCategory = '';
                    // Generate dropdown options
                    while ($row = mysqli_fetch_assoc($subcategoryResult)) {
                        if ($lastCategory !== $row['category_id']) {

                            $categoryName = getCategoryNameById($row['category_id']);

                            echo "<optgroup label='> $categoryName <'></optgroup>";

                            $lastCategory = $row['category_id'];
                        }
                        echo "<option value='" . $row['subcategory_id'] . "' data-category='" . $row['category_id'] . "'>" . $row['subcategory_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="w3-half w3-padding">
                <!-- Brand -->
                <label for="brand_id">Merk:</label>
                <select class="w3-select" name="brand_id" id="brand_id" required>
                    <option value="" disabled selected>Please select</option>
                    <?php
                    // Retrieve brand data from the brand table
                    $brandQuery = "SELECT brand_id, brand_name FROM brand";
                    $brandResult = mysqli_query($conn, $brandQuery);

                    // Generate dropdown options
                    while ($row = mysqli_fetch_assoc($brandResult)) {
                        echo "<option value='" . $row['brand_id'] . "'>" . $row['brand_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

    </div>

    <!-- Right column 50% -->
    <div class="w3-half">

        <div class="w3-container">
            <!-- Article ID -->
            <div class="w3-half w3-padding">
                <label for="article_id">No. Artikel:</label>
                <input class="w3-input" type="text" name="article_id" id="article_id" placeholder="01-234-R-XX" required>
            </div>


            <div class="w3-half w3-padding">
                <!-- Rib -->
                <label for="is_rib">Rib:</label><br>
                <input class="w3-check" type="checkbox" name="is_rib" id="is_rib" value="1"><br><br>
            </div>

        </div>

        <div class="w3-container" style="">
            <!-- Sample Image Upload -->
            <div class="w3-half w3-padding">
                <label for="image">Upload Sample Image:</label>
                <input class="w3-input" type="file" id="art_image" name="art_image" accept="image/*">
            </div>

            <!-- Sample Code -->
            <div class="w3-half w3-padding">
                <label for="sample_code">Sample Code:</label>
                <input class="w3-input" type="text" name="sample_code" id="sample_code" placeholder="Kode Sample" required>
            </div>
        </div>


    </div>


    <div class="w3-half">
        <div class="w3-container">
            <!-- Embro CMT -->
            <div class="w3-padding w3-container w3-half">
                <label class="" for="embro_cmt_id">Embro:</label>
                <select class="w3-select" name="embro_cmt_id" id="embro_cmt_id" required>
                    <option value="" disabled selected>Please select</option>
                    <?php
                    // Retrieve embro data from the cmt table where cmt_type is CT2
                    $embroQuery = "SELECT cmt_id, cmt_name FROM cmt WHERE cmt_type = 'CT2'";
                    $embroResult = mysqli_query($conn, $embroQuery);

                    // Generate dropdown options
                    while ($row = mysqli_fetch_assoc($embroResult)) {
                        echo "<option value='" . $row['cmt_id'] . "'>" . $row['cmt_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Print CMT -->
            <div class="w3-container w3-half w3-padding">
                <label for="print_cmt_id">Print:</label>
                <select class="w3-select" name="print_cmt_id" id="print_cmt_id" required>
                    <option value="" disabled selected>Please select</option>
                    <?php
                    // Retrieve print data from the cmt table where cmt_type is CT2
                    $printQuery = "SELECT cmt_id, cmt_name FROM cmt WHERE cmt_type = 'CT2'";
                    $printResult = mysqli_query($conn, $printQuery);

                    // Generate dropdown options
                    while ($row = mysqli_fetch_assoc($printResult)) {
                        echo "<option value='" . $row['cmt_id'] . "'>" . $row['cmt_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>


    <div class="w3-half w3-padding">
        <div class="w3-container">
            <label for="wash_id">Washing:</label>
            <select class="w3-select" name="wash_id[]" id="wash_id" multiple required>
                <?php
                // Retrieve wash data from the wash_type table
                $washQuery = "SELECT wash_type_id, wash_type_name FROM wash_type";
                $washResult = mysqli_query($conn, $washQuery);

                // Generate dropdown options
                while ($row = mysqli_fetch_assoc($washResult)) {
                    echo "<option value='" . $row['wash_type_id'] . "'>" . $row['wash_type_name'] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="w3-half w3-padding w3-center">
        <input class="w3-button w3-padding-16 w3-blue w3-round-xxlarge" type="submit" value="Submit" style="width: 50%">
    </div>

    <!-- Hidden Input: Category -->
    <div class="w3-padding w3-container ">
        <label for="category_id"></label>
        <input class="" name="category_id" id="category_id" required hidden>
    </div>

</form>




<script>
    // Get the subcategory dropdown element
    var subcategoryDropdown = document.getElementById("subcategory_id");
    // Get the category_id hidden input element
    var categoryInput = document.getElementById("category_id");

    // Add event listener for the subcategory dropdown change event
    subcategoryDropdown.addEventListener("change", function() {
        // Get the selected option
        var selectedOption = subcategoryDropdown.options[subcategoryDropdown.selectedIndex];
        // Get the category_id value from the selected option's data attribute
        var categoryValue = selectedOption.getAttribute("data-category");
        // Set the category_id hidden input value
        categoryInput.value = categoryValue;
    });
</script>


</body>