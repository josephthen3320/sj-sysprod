<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';

function checkSuratJalanExists($sjid) {
    $conn = getConnTransaction();
    $sql = "SELECT * FROM surat_jalan WHERE surat_jalan_id = '$sjid'";
    $row = queryDatabase($conn, $sql);
    $conn->close();

    if ($row == 1) {
        return 1;
    } else {
        return 0;
    }

}

function checkSuratJalanPrinted($tid) {
    $conn = getConnTransaction();
    $sql = "SELECT is_print FROM surat_jalan WHERE transaction_id = '$tid' AND surat_jalan_id NOT LIKE 'SCTO%'";
    $row = queryDatabase($conn, $sql)['is_print'];
    $conn->close();

    return $row;
}

function checkSuratJalanCOPrinted($tid) {
    $conn = getConnTransaction();
    $sql = "SELECT is_print FROM surat_jalan WHERE transaction_id = '$tid' AND surat_jalan_id LIKE 'SCTO%'";
    $row = queryDatabase($conn, $sql)['is_print'];
    $conn->close();

    return $row;
}

function checkSuratJalanExistsByTransactionId($tid) {
    $conn = getConnTransaction();
    $sql = "SELECT * FROM surat_jalan WHERE transaction_id = '$tid' AND surat_jalan_id NOT LIKE 'SCTO%'";
    $row = queryDatabase($conn, $sql);
    $conn->close();

    if (isset($row)) {
        return 1;
    } else {
        return 0;
    }

}

function checkSuratKantorExistsByTransactionId($tid) {
    $conn = getConnTransaction();
    $sql = "SELECT * FROM surat_jalan WHERE transaction_id = '$tid' AND surat_jalan_id LIKE 'SCTO%'";
    $row = queryDatabase($conn, $sql);
    $conn->close();

    if (isset($row)) {
        return 1;
    } else {
        return 0;
    }

}

function createSuratJalan($prefix, $tid, $source, $destination, $article_id, $qty, $uid) {
    $conn = getConnTransaction();

    $sjid = generateSuratJalanIDString($prefix);

    $sql = "INSERT INTO surat_jalan (surat_jalan_id, transaction_id, type, source, destination, article_id, qty, created_by) 
            VALUES ('$sjid', '$tid', 1, '$source', '$destination', '$article_id', '$qty', '$uid')";
    $conn->query($sql);
    $conn->close();

    return $sjid;
}

function createSuratJalanMulti($prefix, $source, $destination, $uid, $data) {
    // $tids && $article_ids && $qtys are array that contain multiple article data

    $conn = getConnTransaction();

    $type = 9;
    $prefix .= "M";

    $totalQty = 0;

    $sjid = generateSuratJalanIDString($prefix);

    foreach ($data as $i => $d) {
        //$tids, $article_ids, $qtys,
        addSuratJalanDetail($sjid, $d[0], $d[1], $d[2]);
        $totalQty += $d[2];
    }

    $sql = "INSERT INTO surat_jalan (surat_jalan_id, type, source, destination, created_by, qty) 
            VALUES ('$sjid', $type, '$source', '$destination', '$uid', $totalQty)";

    $conn->query($sql);



    $conn->close();

    return $sjid;
}

function addSuratJalanDetail($sjid, $transactionId, $articleId, $qty) {
    $conn = getConnTransaction();
    $sql = "INSERT INTO surat_jalan_detail (surat_jalan_id, transaction_id, article_id, qty) 
            VALUES ('$sjid', '$transactionId', '$articleId', $qty)";
    $conn->query($sql);
    $conn->close();
}

function createSuratTerima($prefix, $tid, $source, $destination, $article_id, $qty, $uid) {
    $conn = getConnTransaction();

    $sjid = generateSuratJalanIDString($prefix);

    $sql = "INSERT INTO surat_jalan (surat_jalan_id, transaction_id, type, source, destination, article_id, qty, created_by) 
            VALUES ('$sjid', '$tid', 0, '$source', '$destination', '$article_id', '$qty', '$uid')";
    $conn->query($sql);
    $conn->close();

    return $sjid;
}

function createSuratCutting($prefix, $tid, $article_id, $qty, $uid) {
    $conn = getConnTransaction();

    $sjid = generateSuratJalanIDString($prefix);

    $sql = "INSERT INTO surat_jalan (surat_jalan_id, transaction_id, type, source, destination, article_id, qty, created_by) 
            VALUES ('$sjid', '$tid', 1, 2, -1,'$article_id', '$qty', '$uid')";
    $conn->query($sql);
    $conn->close();

    return $sjid;
}

function addSuratJalanRecord($sjid, $process_name, $tid) {
    $conn = getConnTransaction();

    $sql = "UPDATE {$process_name} SET sj_id = '$sjid' WHERE {$process_name}_id = '$tid'";
    $conn->query($sql);
    $conn->close();
}

function addSuratTerimaRecord($stid, $process_name, $tid) {
    $conn = getConnTransaction();

    $sql = "UPDATE {$process_name} SET st_id = '$stid' WHERE {$process_name}_id = '$tid'";
    $conn->query($sql);
    $conn->close();
}

function addSJCuttingRecord($sjcid, $process_name, $tid) {
    $conn = getConnTransaction();

    $sql = "UPDATE {$process_name} SET sjc_id = '$sjcid' WHERE {$process_name}_id = '$tid'";
    $conn->query($sql);
    $conn->close();
}

function generateSuratJalanIDString($prefix) {
    $conn = getConnTransaction();

    $prefix = "S" . $prefix;

    $currentYear = date('Y');
    $currentMonth = date('m');

    $sql = "SELECT MAX(SUBSTRING_INDEX(surat_jalan_id, '-', -1)) AS last_index FROM surat_jalan WHERE surat_jalan_id LIKE '" . $prefix . $currentYear . $currentMonth . "%'";
    $row = queryDatabase($conn, $sql);
    $conn->close();

    $lastIndex = $row['last_index'];

    // Check if it's the first record of the year
    if ($lastIndex === null) {
        // Set the counter to 1 for the new year
        $newIndex = '001';
    } else {
        // Increment the counter for the new PMID
        $newIndex = str_pad($lastIndex + 1, 3, '0', STR_PAD_LEFT);
    }

    return $prefix . $currentYear . $currentMonth . "-" . $newIndex;
}

function getSuratJalanInternalId($sjid) {
    $conn = getConnTransaction();
    $sql = "SELECT id FROM surat_jalan WHERE surat_jalan_id = '$sjid'";
    $row = queryDatabase($conn, $sql);
    $conn->close();
    return $row['id'];
}


function getSuratJalanDetail($id) {
    $conn = getConnTransaction();

    $sql = "SELECT * FROM surat_jalan WHERE id = '$id'";
    $row = queryDatabase($conn, $sql);
    $conn->close();

    return $row;
}

function getSuratJalanDetailBySJID($sjid) {
    $conn = getConnTransaction();

    $sql = "SELECT * FROM surat_jalan WHERE surat_jalan_id = '$sjid'";
    $row = queryDatabase($conn, $sql);
    $conn->close();

    return $row;
}

function getProcessName($process_id) {
    $conn = getConnProduction($process_id);

    $sql = "SELECT process_name FROM process_list WHERE id = '$process_id'";
    $row = queryDatabase($conn, $sql);
    $conn->close();

    return $row['process_name'];
}



/** Helper Functions */
function queryDatabase($conn, $sql) {

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row;
}

function fetchSuratJalanMultiData($sjid) {
    $conn = getConnTransaction();
    $sql = "SELECT * FROM surat_jalan_detail WHERE surat_jalan_id = '$sjid'";
    $result = $conn->query($sql);

    return $result;
}