<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';

function updateWorksheetPosition($wid, $pid) {
    $conn = getConnTransaction();

    // Check if worksheet_id exists
    $checkSql = "SELECT COUNT(*) as count FROM position WHERE worksheet_id = '$wid'";
    $checkResult = $conn->query($checkSql);
    $row = $checkResult->fetch_assoc();
    $recordCount = $row['count'];

    if ($recordCount > 0) {
        // Update the existing record
        $updateSql = "UPDATE position SET position_id = $pid WHERE worksheet_id = '$wid'";
        $conn->query($updateSql);
    } else {
        // Insert a new record
        $insertSql = "INSERT INTO position (worksheet_id, position_id) VALUES ('$wid', $pid)";
        $conn->query($insertSql);
    }

    $conn->close();
}

function getWorksheetPosition($a) {
    $conn = getConnTransaction();

    $checkSql = "SELECT position_id FROM position WHERE worksheet_id = '$a'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // Worksheet position exists, return the position_id
        $row = $checkResult->fetch_assoc();
        $positionId = $row['position_id'];
        $conn->close();
        return $positionId;
    } else {
        // Worksheet position does not exist
        $conn->close();
        return -1; // Or you can return a default value or handle the case accordingly
    }
}

function getPositionStatus($a, $pn) {
    $conn = getConnTransaction();

    $checkSql = "SELECT $pn FROM position WHERE worksheet_id = '$a'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // Worksheet position exists, return the position_id
        $row = $checkResult->fetch_assoc();
        $positionId = $row[$pn];
        $conn->close();
        return $positionId;
    } else {
        // Worksheet position does not exist
        $conn->close();
        return -1; // Or you can return a default value or handle the case accordingly
    }
}

function parseWorksheetPosition($pos) {
    switch ($pos) {
        case -2:
            return "TRANSIT";
        case -1:
            return "KANTOR";
        case 0:
            return "WORKSHEET";
        case 1:
            return "POLA MARKER";
        case 2:
            return "CUTTING";
        case 3:
            return "EMBRO";
        case 4:
            return "PRINT/SABLON";
        case 5:
            return "QC EMBRO";
        case 6:
            return "SEWING";
        case 7:
            return "WASHING";
        case 8:
            return "FINISHING";
        case 9:
            return "QC FINAL";
        case 10:
            return "PERBAIKAN";
        case 11:
            return "GUDANG";
        default:
            return "UNKNOWN";
    }
}
