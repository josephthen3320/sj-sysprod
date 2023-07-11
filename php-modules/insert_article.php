<!-- insert_article.php -->
<?php
session_start();
// Include the database connection file
require_once 'db.php';
$conn = getConnProduction();

// Retrieve form data
$model_name = $_POST['model_name'];
$article_id = $_POST['article_id'];
$category_id = $_POST['category_id'];
$subcategory_id = $_POST['subcategory_id'];
$embro_cmt_id = $_POST['embro_cmt_id'];
$print_cmt_id = $_POST['print_cmt_id'];
$wash_ids = $_POST['wash_id'];
$brand_id = $_POST['brand_id'];
$is_rib = isset($_POST['is_rib']) ? 1 : 0;
$sample_code = $_POST['sample_code'];

// Retrieve the uploaded image file
$file = $_FILES['art_image'];
$filename = $file['name'];
$file_tmp = $file['tmp_name'];

// Generate the target directory and file path
$target_directory = $_SERVER['DOCUMENT_ROOT'] . "/img/articles/";
$src_path = "/img/articles/";
$extension = pathinfo($filename, PATHINFO_EXTENSION);

// Find the latest index for the subcategory
$latest_index = 1;
$existing_files = glob($target_directory . $category_id . '-' . trim($subcategory_id, 'A-Za-z') . '*');
if (!empty($existing_files)) {
    $latest_file = max($existing_files);
    $latest_index = (int) substr($latest_file, -7, 3) + 1;
}

// Pad the index with leading zeros
$padded_index = str_pad($latest_index, 3, '0', STR_PAD_LEFT);

// Generate the new file name
$new_filename = $category_id . '-' . trim($subcategory_id, 'A-Za-z') . '-' . $padded_index . '.' . $extension;
$target_filepath = $target_directory . $new_filename;

// Move the uploaded file to the target directory
if (move_uploaded_file($file_tmp, $target_filepath)) {
    /**/
    // Check if the file needs to be converted to WebP
    if ($extension !== 'webp') {
        // Generate the new file name for the converted WebP image
        $new_filename_webp = $category_id . '-' . trim($subcategory_id, 'A-Za-z') . '-' . $padded_index . '.webp';
        $converted_filepath_webp = $target_directory . $new_filename_webp;

        // Create a new image from the uploaded file
        if (in_array($extension, ['png', 'gif', 'jpg', 'jpeg'])) {
            switch ($extension) {
                case 'png':
                    $image = imagecreatefrompng($target_filepath);
                    break;
                case 'gif':
                    $image = imagecreatefromgif($target_filepath);
                    break;
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($target_filepath);
                    break;
                default:
                    $image = false;
            }
        } else {
            // Handle unsupported image formats
            echo "Unsupported image format.";
        }

        // Convert and save the image as WebP
        if ($image !== false) {
            if (imagewebp($image, $converted_filepath_webp, 80)) {
                // Remove the original image file
                unlink($target_filepath);
                // Update the image path to point to the converted WebP image
                $target_filepath = $converted_filepath_webp;
            } else {
                // Handle the case where image conversion failed
                echo "Error converting image to WebP.";
            }
        } else {
            // Handle the case where image creation failed
            echo "Error creating image from file.";
        }
    }

    /*/
    // Check if the file needs to be converted to JPEG
    if ($extension !== 'jpg' && $extension !== 'jpeg') {
        // Generate the new file name for the converted JPEG image
        $new_filename_jpg = $category_id . '-' . trim($subcategory_id, 'A-Za-z') . '-' . $padded_index . '.jpg';
        $converted_filepath_jpg = $target_directory . $new_filename_jpg;

        // Create a new image from the uploaded file
        if ($extension === 'png') {
            $image = imagecreatefrompng($target_filepath);
        } elseif ($extension === 'gif') {
            $image = imagecreatefromgif($target_filepath);
        }

        // Convert and save the image as JPEG
        if ($image !== false) {
            if (imagejpeg($image, $converted_filepath_jpg, 50)) {
                // Remove the original image file
                unlink($target_filepath);
                // Update the image path to point to the converted JPEG image
                $target_filepath = $converted_filepath_jpg;
            } else {
                // Handle the case where image conversion failed
                echo "Error converting image to JPEG.";
            }
        } else {
            // Handle the case where image creation failed
            echo "Error creating image from file.";
        }
    }/**/

    // Prepare the insert statement
    $insertQuery = "INSERT INTO article (model_name, article_id, category_id, subcategory_id, embro_cmt_id, print_cmt_id, brand_id, is_rib, sample_code, sample_img_path) VALUES ('$model_name', '$article_id', '$category_id', '$subcategory_id', '$embro_cmt_id', '$print_cmt_id', '$brand_id', $is_rib, '$sample_code', '$new_filename_webp')";

    // Execute the insert statement
    if (mysqli_query($conn, $insertQuery)) {
        echo "Data inserted successfully!";
        $_SESSION['msg'] = "<div class='w3-bar w3-green'><span class='w3-bar-item'>Insert Success!</span></div>";

    } else {
        $_SESSION['msg'] = "<div class='w3-bar w3-red'><span class='w3-bar-item'>Error: " . mysqli_error($conn) . "</span></div>";
        echo "<BR><BR>{$target_filepath}<br><br>";
        unlink($target_filepath);

        echo "Error inserting data: " . mysqli_error($conn);
        // Delete image file
    }

    // Insert the associations into the article_wash table
    foreach ($wash_ids as $washId) {
        $insertWashQuery = "INSERT INTO article_wash (article_id, wash_id) VALUES ('$article_id', '$washId')";
        mysqli_query($conn, $insertWashQuery);
    }
} else {
    // Handle the case where file upload failed
    echo "Error uploading file: " . $_FILES['art_image']['error'];
    $_SESSION['msg'] = "<div class='w3-bar w3-red'><span class='w3-bar-item'>Error: " . $_FILES['art_image']['error'] . "</span></div>";
}



header("Location: {$_SERVER['HTTP_REFERER']}");
exit();















/*
// Include the database connection file
include 'db_prod.php';

// Retrieve form data
$model_name = $_POST['model_name'];
$article_id = $_POST['article_id'];
$category_id = $_POST['category_id'];
$subcategory_id = $_POST['subcategory_id'];
$embro_cmt_id = $_POST['embro_cmt_id'];
$print_cmt_id = $_POST['print_cmt_id'];
$wash_ids = $_POST['wash_id'];
$brand_id = $_POST['brand_id'];
$is_rib = isset($_POST['is_rib']) ? 1 : 0;
$sample_code = $_POST['sample_code'];


// Print out the submitted input
echo "Submitted Input:<br>";
echo "Article Name: " . $model_name . "<br>";
echo "Article ID: " . $article_id . "<br>";
echo "Category ID: " . $category_id . "<br>";
echo "Subcategory ID: " . $subcategory_id . "<br>";
echo "Embro ID: " . $embro_cmt_id . "<br>";
echo "Print ID: " . $print_cmt_id . "<br>";
echo "Wash IDs: " . implode(", ", $wash_ids) . "<br>";
echo "Brand ID: " . $brand_id . "<br>";
echo "Rib: " . $is_rib . "<br>";
echo "Sample Code: " . $sample_code . "<br>";


// Prepare the insert statement
$insertQuery = "INSERT INTO article (model_name, article_id, category_id, subcategory_id, embro_cmt_id, print_cmt_id, brand_id, is_rib, sample_code) VALUES ('$model_name', '$article_id', '$category_id', '$subcategory_id', '$embro_cmt_id', '$print_cmt_id', '$brand_id', $is_rib, '$sample_code')";

// Execute the insert statement
if (mysqli_query($conn, $insertQuery)) {
  echo "Data inserted successfully!";
} else {
  echo "Error inserting data: " . mysqli_error($conn);
}

// Insert the associations into the article_wash table
foreach ($wash_ids as $washId) {
    $insertWashQuery = "INSERT INTO article_wash (article_id, wash_id) VALUES ('$article_id', '$washId')";
    mysqli_query($conn, $insertWashQuery);
}


// Close the database connection
mysqli_close($conn);
*/
?>
