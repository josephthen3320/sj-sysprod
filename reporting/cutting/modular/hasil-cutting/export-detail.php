<?php
// Include the PhpSpreadsheet classes
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
$conn = getConnTransaction();

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data from the database
$sql = "SELECT * FROM cutting WHERE date_cut IS NOT null ORDER BY date_cut DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Create a new array to store the data for Excel export
    $excelData = array();

    $totalQty = 0;

    while ($row = $result->fetch_assoc()) {
        $article_id = fetchWorksheet($row['worksheet_id'])->fetch_assoc()['article_id'];
        $article = getArticleById($article_id);
        $cmtName = getCMTNameById($row['cmt_id']);

        // Add the row data to the Excel data array
        $excelData[] = array(
            'DATE' => $row['date_cut'],
            'No. Worksheet' => $row['worksheet_id'],
            'QTY' => $row['qty_out'],
            'Location' => $cmtName,
            'No. Artikel' => $article['article_id'],
            'Model' => $article['model_name']
        );

        $totalQty += $row['qty_out'];
    }

    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();

    // Set the active sheet
    $sheet = $spreadsheet->getActiveSheet();

    // Set the headers
    $headers = array_keys($excelData[0]);
    $sheet->fromArray($headers, NULL, 'A1');

    // Set the font style for the headers to bold
    $headerStyle = $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1');
    $headerStyle->getFont()->setBold(true);

    // Set the data from the Excel data array
    $row = 2;
    foreach ($excelData as $data) {
        $column = 'A';
        foreach ($data as $value) {
            $sheet->setCellValue($column . $row, $value);
            $column++;
        }
        $row++;
    }

    // Create a new Excel file
    $writer = new Xlsx($spreadsheet);

    $currentDate = date('Ymd-His');





    $file_name = 'laporan_hasil_cutting_'. $currentDate . '.xlsx'; // Change the file name as needed

    // Save the file to the server
    $writer->save($file_name);

    // Provide the file for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
    header('Content-Length: ' . filesize($file_name));
    readfile($file_name);

    // Delete the temporary file
    unlink($file_name);
} else {
    echo "No data found.";
}

// Close the database connection
$conn->close();
