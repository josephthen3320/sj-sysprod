<?php
// Include the PhpSpreadsheet classes
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
$conn = getConnTransaction();

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data from the database
//$sql = "SELECT * FROM cutting WHERE date_in IS NOT null ORDER BY date_in DESC";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit();
}

$sql = $_POST['sql'];
$result = $conn->query($sql);

$cmt_id_label = $_POST['fLocation'];
$start_date_label = $_POST['fStartDate'];
$end_date_label = $_POST['fEndDate'];

if ($cmt_id_label == "") {
    $cmt_id_label = "ALL";
} else {
    $cmt_id_label = getCMTNameById($cmt_id_label);
}

if ($start_date_label == "" ) {
    $period_label = "ALL";
} else {
    $period_label = $start_date_label . " - " . $end_date_label;
}

if ($result->num_rows > 0) {
    // Create a new array to store the data for Excel export
    $excelData = array();

    $totalQty = 0;

    while ($row = $result->fetch_assoc()) {
        $article_id = fetchWorksheet($row['worksheet_id'])->fetch_assoc()['article_id'];
        $article = getArticleById($article_id);
        $cmtName = getCMTNameById($row['cmt_id']);

        $categoryName = getCategoryNameById($article['category_id']);

        // Add the row data to the Excel data array
        $excelData[] = array(
            'DATE' => $row['date_in'],
            'No. Worksheet' => $row['worksheet_id'],
            'QTY' => $row['qty_in'],
            'Proses' => 'JAHIT',
            'CMT' => $cmtName,
            'No. Artikel' => $article['article_id'],
            'Model' => $article['model_name'],
            'Category' => $categoryName
        );

        $totalQty += $row['qty_in'];
    }

    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();

    // Set the active sheet
    $sheet = $spreadsheet->getActiveSheet();

    // Set the headers
    $headers = array_keys($excelData[0]);
    $sheet->fromArray($headers, NULL, 'B4');

    // Set the font style for the headers to bold
    $headerStyle = $sheet->getStyle('B4:' . $sheet->getHighestColumn() . '4');
    $headerStyle->getFont()->setBold(true);

    $sheet->setCellValue('B1', "Laporan Pengiriman Hasil Cutting ke Sewing CMT");
    $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(18);
    $sheet->mergeCells('B1:I1'); // Merging cells from column B to G in row 1

    // Apply center alignment to the merged cell
    $style = $sheet->getStyle('B1:I1');
    $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


    $sheet->setCellValue('B2', "CMT:");
    $sheet->setCellValue('C2', "{$cmt_id_label}");
    $sheet->setCellValue('E2', "Periode:");
    $sheet->setCellValue('F2', "{$period_label}");

    $sheet->getStyle('B2')->getFont()->setBold(true);
    $sheet->getStyle('E2')->getFont()->setBold(true);


    // Set the data from the Excel data array
    $row = 5;
    $totalQtyLabel = 0;
    foreach ($excelData as $data) {
        $column = 'B';
        foreach ($data as $value) {
            $sheet->setCellValue($column . $row, $value);

            // Apply normal (default) border to the cell
            $sheet->getStyle($column . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            if ($column == "D") {
                $totalQtyLabel += $value;
            }

            $column++;
        }
        $row++;
    }

    $sheet->setCellValue("C".$row, "Total:");
    $sheet->setCellValue("D".$row, $totalQtyLabel);
    $sheet->getStyle('C'.$row)->getFont()->setBold(true);
    $sheet->getStyle('C'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


    $row += 2;

    $sheet->setCellValue("B".$row, "Dibuat oleh,");
    $sheet->setCellValue("G".$row, "Mengetahui,");
    $sheet->getStyle('B'.$row)->getFont()->setBold(true);
    $sheet->getStyle('G'.$row)->getFont()->setBold(true);


    $row += 4;

    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/logging/get_user_information.php';

    $username = $_SESSION['username'];
    $userFullName = getUserFullnameByUsername($username);

    $sheet->setCellValue("B".$row, $userFullName);
    $sheet->setCellValue("G".$row, "Manager Produksi");

    // Define the RGB color code for light orange (255, 204, 153)
    $lightOrangeColor = 'FFCC99'; // ARGB format: AARRGGBB

    // Set the background color of cells B4 to F4 to light orange
    $sheet->getStyle('B4:I4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($lightOrangeColor);

    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                'color' => ['argb' => '000000'], // Black color
            ],
        ],
    ];

    $sheet->getStyle('B4:I4')->applyFromArray($styleArray);

    // Set the column width of B to G to autofit
    for ($col = 'B'; $col <= 'I'; $col++) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Create a new Excel file
    $writer = new Xlsx($spreadsheet);

    $currentDate = date('Ymd-His');





    $file_name = 'laporan_kirim_hasil_cutting_ke_sewing_'. $currentDate . '.xlsx'; // Change the file name as needed

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
