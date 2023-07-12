<?php

function getDbConnection($database) {
    $servername = "localhost";
    $dbusername = "subm6595_sj";
    $dbpassword = "Suburjaya112256";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function getConnTransaction() {
    return getDbConnection("subm6595_sj_transaction");
}

function getConnProduction() {
    return getDbConnection("subm6595_sj_production");
}

function getConnWorksheet() {
    return getDbConnection("subm6595_sj_worksheet");
}

function getConnLog() {
    return getDbConnection("subm6595_sj_logs");
}

function getConnUser() {
    return getDbConnection("subm6595_sj_accounts");
}
?>
