<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnProduction();

/*
 * Input:   N/A
 * Output:  CMTs
 */

    $query              = "SELECT * FROM cmt";
    $cmt_results        = $conn->query($query);

    return $cmt_results;


