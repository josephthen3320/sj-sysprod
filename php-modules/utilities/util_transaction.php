<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/agents/logging.php';

// TRANSACTION CODES GENERATOR
function generateIDString($prefix, $process_name) {
    $currentYear = date('Y');

    $conn = getConnTransaction();
    $sql = "SELECT MAX(SUBSTRING_INDEX(" . $process_name . "_id, '-', -1)) AS last_counter FROM " . $process_name . " WHERE " . $process_name . "_id LIKE '" . $prefix . $currentYear . "%'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $conn->close();

    $lastCounter = $row['last_counter'];

    // Check if it's the first record of the year
    if ($lastCounter === null) {
        // Set the counter to 1 for the new year
        $newCounter = '001';
    } else {
        // Increment the counter for the new PMID
        $newCounter = str_pad($lastCounter + 1, 3, '0', STR_PAD_LEFT);
    }

    return $prefix . $currentYear . "-" . $newCounter;
}


// POLA MARKER FUNCTIONS


function updatePolaMarkerEndDate($id) {
    $conn = getConnTransaction();

    updateEndDate('pola_marker', $id);

}


/** Generic Functions */
function pushToProcess($p, $pid, $w) {
    $conn = getConnTransaction();
    $uid = $_SESSION['user_id'];


    if (func_get_args()[3]) {
        $qty_in = func_get_args()[3];

        $sql = "INSERT INTO ".$p." (".$p."_id, worksheet_id, created_by, qty_in)
            VALUES ('$pid', '$w', '$uid', '$qty_in')";
    } else {
        $sql = "INSERT INTO ".$p." (".$p."_id, worksheet_id, created_by)
            VALUES ('$pid', '$w', '$uid')";
    }

    $conn->query($sql);
    $conn->close();
}

function pushToProcessCuttingTest($p, $pid, $w, $cmt) {
    $conn = getConnTransaction();
    $uid = $_SESSION['user_id'];

    $sql = "INSERT INTO ".$p." (".$p."_id, worksheet_id, created_by, cmt_id)
        VALUES ('$pid', '$w', '$uid', '$cmt')";

    $conn->query($sql);
    $conn->close();
}

/**
 * Push the current process to another and requires CMT ID
 *
 * @param $p    string      The target process name (e.g., pola_marker)
 * @param $pid  int         The target process ID
 * @param $w    string      Worksheet ID
 * @param $cmt  string      CMT ID
 */
function pushToProcessWithCMT($p, $pid, $w, $cmt, $qty_in) {
    $conn = getConnTransaction();
    $uid = $_SESSION['user_id'];

    $sql = "INSERT INTO ".$p." (".$p."_id, worksheet_id, created_by, cmt_id, qty_in)
        VALUES ('$pid', '$w', '$uid', '$cmt', '$qty_in')";

    $conn->query($sql);
    $conn->close();
}

function setQtyIn($p, $pid, $qty) {
    $conn = getConnTransaction();
    $sql = "UPDATE $p SET qty_out = $qty WHERE id = '$p'";
    $conn->query($sql);
    $conn->close();
}

function setQtyOut($p, $pid, $qtyOut) {
    $conn = getConnTransaction();

    $sql = "UPDATE $p SET qty_out = $qtyOut WHERE {$p}_id = '$pid'";
    $conn->query($sql);
    $conn->close();
}


function fetchAllTransactionByProcess($tname) {
    $conn = getConnTransaction();

    $sql = "SELECT t.*, p.position_id, p.{$tname} FROM {$tname} AS t INNER JOIN position AS p ON t.worksheet_id = p.worksheet_id ORDER BY p.{$tname} ASC, t.{$tname}_id DESC";
    //$sql = "SELECT * FROM {$tname} AS t INNER JOIN position AS p ON t.worksheet_id = p.worksheet_id ORDER BY p.{$tname} ASC, t.{$tname}_id DESC";
    $result = $conn->query($sql);
    $conn->close();
    return $result;
}

function fetchCuttingCipadungTransaction() {
    $conn = getConnTransaction();

    $sql = "SELECT * FROM cutting WHERE cmt_id = 'KB01' ORDER BY date_in DESC";
    $result = $conn->query($sql);
    $conn->close();
    return $result;
}

function updateEndDate ($tn, $tid) {                // table {transaction_name}
    $conn = getConnTransaction();

    echo $sql = "UPDATE " . $tn . " SET date_out = '" . date('Y-m-d H:i:s') . "' WHERE id = '" . $tid . "'";
    $conn->query($sql);
    $conn->close();
}

function toggleProcessCompleted($tn, $wid) {
    $conn = getConnTransaction();
    $sql = "UPDATE position SET $tn = 1 WHERE worksheet_id = '$wid'";
    $conn->query($sql);
    $conn->close();

}

/** Sewing Helper Updates */
function setQtyOther($p, $pid, $qtyDefect, $qtyFail, $qtyMissing) {
    $conn = getConnTransaction();

    $sql = "UPDATE $p SET qty_defect = $qtyDefect, qty_fail = $qtyFail, qty_missing = $qtyMissing WHERE {$p}_id = '$pid'";
    $conn->query($sql);
    $conn->close();
}


/** Push functions */
function pushToPolaMarker($wid) {
    $recordId = generatePolaMarkerId();
    pushToProcess("pola_marker", $recordId, $wid);
    logGeneric(-1, 501, "RECORD GENERATED; worksheet_id={$wid}; record_id={$recordId};");
}

function pushToCutting($wid, $cmt) {
    //pushToProcess('cutting', generateCuttingId(), $wid);
    $recordId = generateCuttingId();
    pushToProcessCuttingTest('cutting', $recordId, $wid, $cmt);
    logGeneric(-1, 511, "RECORD GENERATED; worksheet_id={$wid}; record_id={$recordId};");
}

function pushToEmbro($wid, $cmt, $qtyIn) {
    $recordId = generateEmbroId();
    pushToProcessWithCMT('embro', $recordId, $wid, $cmt, $qtyIn);
    logGeneric(-1, 521, "RECORD GENERATED; worksheet_id={$wid}; record_id={$recordId};");
}

function pushToPrintSablon($wid, $cmt, $qtyIn) {
    $recordId = generatePrintSablonId();
    pushToProcessWithCMT('print_sablon', $recordId, $wid, $cmt, $qtyIn);
    logGeneric(-1, 531, "RECORD GENERATED; worksheet_id={$wid}; record_id={$recordId};");
}

function pushToQCEmbro($wid, $qtyIn) {
    $recordId = generateQCEmbroId();
    pushToProcess('qc_embro', $recordId, $wid, $qtyIn);
    logGeneric(-1, 541, "RECORD GENERATED; worksheet_id={$wid}; record_id={$recordId};");
}

function pushToSewing($wid, $cmt, $qtyIn) {
    $recordId = generateSewingId();
    pushToProcessWithCMT('sewing', $recordId, $wid, $cmt, $qtyIn);
    logGeneric(-1, 551, "RECORD GENERATED; worksheet_id={$wid}; record_id={$recordId};");
}

function pushToWashing($wid, $cmt, $qtyIn) {
    $recordId = generateWashingId();
    pushToProcessWithCMT('washing', $recordId, $wid, $cmt, $qtyIn);
    logGeneric(-1, 561, "RECORD GENERATED; worksheet_id={$wid}; record_id={$recordId};");
}

function pushToFinishing($wid, $cmt, $qtyIn) {
    $recordId = generateFinishingId();
    pushToProcessWithCMT('finishing', $recordId, $wid, $cmt, $qtyIn);
    logGeneric(-1, 571, "RECORD GENERATED; worksheet_id={$wid}; record_id={$recordId};");
}

function pushToQCFinal($wid, $qtyIn) {
    $recordId = generateQCFinalId();
    pushToProcess('qc_final', $recordId, $wid, $qtyIn);
    logGeneric(-1, 581, "RECORD GENERATED; worksheet_id={$wid}; record_id={$recordId};");
}

function pushToPerbaikan($wid, $qtyIn) {
    $recordId = generateServiceId();
    pushToProcess('perbaikan', $recordId, $wid, $qtyIn);
    logGeneric(-1, 591, "RECORD GENERATED; worksheet_id={$wid}; record_id={$recordId};");
}


/** ID Generator */
function generatePolaMarkerId() {
    return generateIDString("PM", "pola_marker");
}

function generateCuttingId() {
    return generateIDString("CT", "cutting");
}

function generateEmbroId() {
    return generateIDString("EB", "embro");
}

function generatePrintSablonId() {
    return generateIDString("EP", "print_sablon");
}

function generateSewingId() {
    return generateIDString("SI", "sewing");
}

function generateQCEmbroId() {
    return generateIDString("QE", "qc_embro");
}

function generateWashingId() {
    return generateIDString("WI", "washing");
}

function generateFinishingId() {
    return generateIDString("FI", "finishing");
}

function generateQCFinalId() {
    return generateIDString("QF", "qc_final");
}

function generateServiceId() {
    return generateIDString("RI", "service");
}

function generateWarehouseId() {
    return generateIDString("GD", "warehouse");
}


/** Check functions */
function checkIsProcessDone ($worksheetId, $processName) {
    $conn = getConnTransaction();
    echo $sql = "SELECT $processName FROM position WHERE worksheet_id = '$worksheetId'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc()[$processName];

    $conn->close();

    return $row;
}

function getWorksheetIdByProcessId($processId) {
    $conn = getConnTransaction();
    $pn = parseProcessNameFromProcessId($processId);

    $sql = "SELECT worksheet_id FROM $pn WHERE {$pn}_id = '$processId' LIMIT 1";
    $worksheetId = queryDatabase($conn, $sql)['worksheet_id'];


    return $worksheetId;
}


function parseProcessNameFromProcessId($processId) {
    $prefix = substr($processId, 0, 2);

    switch ($prefix) {
        case 'PM':
            return "pola_marker";
        case 'CT':
            return "cutting";
        default:
            return null;
    }
}