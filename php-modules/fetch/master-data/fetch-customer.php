<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnProduction();

/*
 * Input:   N/A
 * Output:  Customers
 */

    $query              = "SELECT * FROM customer";
    $customer_results   = $conn->query($query);

    return $customer_results;


