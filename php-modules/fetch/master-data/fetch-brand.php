<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnProduction();

/*
 * Input:   N/A
 * Output:  Brands
 */

    $query              = "SELECT * FROM brand";
    $brand_results      = $conn->query($query);

    return $brand_results;


