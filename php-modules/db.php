<?php

function getDbConnection($database) {
    $servername = "localhost";
    $dbusername = "nara";
    $dbpassword = "12345678";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function getConnTransaction() {
    return getDbConnection("suburjaya_transaction");
}

function getConnProduction() {
    return getDbConnection("suburjaya_production");
}

function getConnWorksheet() {
    return getDbConnection("suburjaya_worksheet");
}

function getConnLog() {
    return getDbConnection("suburjaya_logs");
}

function getConnUser() {
    return getDbConnection("suburjaya");
}
?>
