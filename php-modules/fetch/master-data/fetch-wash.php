<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnProduction();

/*
 * Input:   N/A
 * Output:  Wash Types
 */

    $query              = "SELECT * FROM wash_type";
    $wash_results       = $conn->query($query);

    return $wash_results;


