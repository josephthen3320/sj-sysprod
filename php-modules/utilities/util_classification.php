<?php


function fetchCategories() {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT * FROM main_category";
    $result = $conn->query($sql);
    $conn->close();

    if ($result) {
        return $result;
    } else {
        return null;
    }
}

function fetchSubcategories() {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT * FROM subcategory";
    $result = $conn->query($sql);
    $conn->close();

    if ($result) {
        return $result;
    } else {
        return null;
    }
}

function fetchBrands() {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT * FROM brand";
    $result = $conn->query($sql);
    $conn->close();

    if ($result) {
        return $result;
    } else {
        return null;
    }
}

function fetchCustomers() {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT * FROM customer";
    $result = $conn->query($sql);
    $conn->close();

    if ($result) {
        return $result;
    } else {
        return null;
    }
}

function fetchWashes() {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT * FROM wash_type";
    $result = $conn->query($sql);
    $conn->close();

    if ($result) {
        return $result;
    } else {
        return null;
    }
}

function fetchCMTs() {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT * FROM cmt";
    $result = $conn->query($sql);
    $conn->close();

    if ($result) {
        return $result;
    } else {
        return null;
    }
}

function fetchAllCMTByType($cmt_type) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT * FROM cmt WHERE cmt_type = '$cmt_type'";
    $result = $conn->query($sql);
    $conn->close();

    if ($result) {
        return $result;
    } else {
        return null;
    }

}


/** Get by ID */
/*
function getCMTNameById($cmt_id) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT cmt_name FROM cmt WHERE cmt_id = '$cmt_id'";
    $result = $conn->query($sql);
    $conn->close();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return $row['cmt_name'];
    } else {
        return null;
    }
}
*/