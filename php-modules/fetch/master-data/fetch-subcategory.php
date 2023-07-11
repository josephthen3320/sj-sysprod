<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnProduction();

/*
 * Input:   N/A
 * Output:  Sub-categories
 */

    $query              = "SELECT * FROM subcategory";
    $subcategory_result = $conn->query($query);

    return $subcategory_result;


