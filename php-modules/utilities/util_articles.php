<?php

function fetchAllArticles() {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT * FROM article ORDER BY id DESC";
    $result = $conn->query($sql);

    $articles = [];

    if ($result) {
        while ($row = $result->fetch_assoc()){
            $articles[] = $row;
        }
    }

    $conn->close();
    return $articles;
}

function getArticleById($id) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT * FROM article WHERE article_id = '$id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $article = $result->fetch_assoc();
        return $article;
    } else {
        return null;
    }
}

function getCategoryNameById($id) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT category_name FROM main_category WHERE category_id = '$id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $category = $result->fetch_assoc()['category_name'];
        return $category;
    } else {
        return null;
    }
}

function getSubcategoryNameById($id) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT subcategory_name FROM subcategory WHERE subcategory_id = '$id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $subcategory = $result->fetch_assoc()['subcategory_name'];
        return $subcategory;
    } else {
        return null;
    }
}

function getBrandNameById($id) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT brand_name FROM brand WHERE brand_id = '$id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $brand = $result->fetch_assoc()['brand_name'];
        return $brand;
    } else {
        return null;
    }
}

function getCMTNameById($id) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT cmt_name FROM cmt WHERE cmt_id = '$id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $cmt = $result->fetch_assoc()['cmt_name'];
        return $cmt;
    } else {
        return null;
    }
}

function getWashNameById($id) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT wash_type_name FROM wash_type WHERE wash_type_id = '$id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $wash = $result->fetch_assoc()['wash_type_name'];
        return $wash;
    } else {
        return null;
    }
}

function fetchWashNamesByArticleId($id) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnProduction();

    $sql = "SELECT wash_id FROM article_wash WHERE article_id = '$id'";
    $result = $conn->query($sql);

    $washes = array();

    if ($result && $result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $washes[] = $row['wash_id'];
        }
    } else {
        return null;
    }

    $washNames = array();

    foreach ($washes as $w) {
        $washNames[] = getWashNameById($w);
    }

    return $washNames;

}