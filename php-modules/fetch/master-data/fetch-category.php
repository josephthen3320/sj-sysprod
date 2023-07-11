<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnProduction();

/*
 * Input:   N/A
 * Output:  Categories
 */

    $query_sorted       = "SELECT * FROM main_category ORDER BY id";    // change the below to sort

    $query              = "SELECT * FROM main_category";
    $category_result    = $conn->query($query);

    return $category_result;


