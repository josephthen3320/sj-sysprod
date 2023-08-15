<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';

function getAllWorksheet(){
    $conn = getConnProduction();

    $sql = "SELECT * FROM worksheet INNER JOIN worksheet_detail ON worksheet.worksheet_id = worksheet_detail.worksheet_id ORDER BY worksheet.id ASC";
    $result = $conn->query($sql);

    $worksheets = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $worksheets[] = $row;
        }
    }

    $conn->close();

    return $worksheets;
}

function fetchWorksheets() {
    $pConn = getConnProduction();
    $tConn = getConnTransaction();
/*
    $sql = "SELECT * FROM worksheet 
            LEFT JOIN suburjaya_transaction.position AS p ON worksheet.worksheet_id = p.worksheet_id
            INNER JOIN worksheet_detail ON worksheet.worksheet_id = worksheet_detail.worksheet_id
            ORDER BY p.position_id ASC, worksheet.id ASC";
*/
    $sql = "SELECT * FROM worksheet 
            LEFT JOIN suburjaya_transaction.position AS p ON worksheet.worksheet_id = p.worksheet_id 
            INNER JOIN worksheet_detail ON worksheet.worksheet_id = worksheet_detail.worksheet_id 
            ORDER BY 
                CASE WHEN p.position_id = 0 THEN 0 ELSE 1 END, -- Put rows with position_id = 0 on top
                worksheet.id DESC; -- Sort by worksheet.id DESC in both cases";

    $result = $pConn->query($sql);

    $worksheets = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $worksheets[] = $row;
        }
    }

    $pConn->close();
    $tConn->close();

    return $worksheets;

}

function getWorksheetIdByGlobalId($id) {
    $conn = getConnProduction();

    $sql = "SELECT worksheet_id FROM worksheet WHERE id = '$id'";
    $result = $conn->query($sql);
    $conn->close();

    $worksheet_id = $result->fetch_assoc()['worksheet_id'];

    return $worksheet_id;
}

function fetchWorksheet($worksheetId) {
    $conn = getConnProduction();

    $sql = "SELECT * FROM worksheet INNER JOIN worksheet_detail ON worksheet.worksheet_id = worksheet_detail.worksheet_id WHERE worksheet.worksheet_id = '$worksheetId'";
    $result = $conn->query($sql);
    $conn->close();

    return $result;
}

function fetchWorksheetData($worksheetId) {
    $conn = getConnProduction();

    $sql = "SELECT * FROM worksheet INNER JOIN worksheet_detail ON worksheet.worksheet_id = worksheet_detail.worksheet_id WHERE worksheet.worksheet_id = '$worksheetId'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $conn->close();

    return $row;

}

function fetchWorksheetDetails($worksheetId) {
    // Include the database connection file
    $conn = getConnProduction();

    // Prepare a parameterized query to fetch data from the "worksheet_detail" table for a specific worksheet ID
    $sql = "SELECT * FROM worksheet_detail WHERE worksheet_id = '$worksheetId'";
    $result = $conn->query($sql);

    // Close the database connection
    $conn->close();

    // Return the details array
    return $result->fetch_assoc();
}


function generateWorksheetId() {
    $conn = getConnProduction();

    $prefix = "WS";
    $year = date('Y');
    $month = date('m');

    // lookup index value
    $sql = "SELECT MAX(SUBSTRING_INDEX(SUBSTRING_INDEX(worksheet_id, '-', -1), '-', 1)) AS last_index FROM worksheet";
    $result = $conn->query($sql);
    $conn->close();

    if ($result) {
        $row = $result->fetch_assoc();
        $lastIndex = $row['last_index'];

        $lastIndex = $lastIndex === null ? 0 : $lastIndex;      // if no record yet in that year, set index = 0
    } else {
        exit();
    }

    ++$lastIndex;

    $lastIndex = str_pad($lastIndex, 3, "0", STR_PAD_LEFT);     // pad index with leading 0s

    $idString = $prefix . $year . "-" . $month . "-" . $lastIndex;  // build the id string

    return $idString;



}