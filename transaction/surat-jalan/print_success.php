<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
$conn = getConnTransaction();

$sjid = $_POST['sjid'];

$sql = "UPDATE surat_jalan SET is_print = 1 WHERE surat_jalan_id = '$sjid'";
$conn->query($sql);
$conn->close();

echo "Printed, logged.";