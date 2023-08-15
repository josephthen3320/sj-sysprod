<?php
// Include the PhpSpreadsheet classes
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_transaction.php';
$conn = getConnTransaction();

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit();
}

$sql = $_POST['sql'];
$sql = "SELECT *, SUM(qty_out) AS total_qty_out
                    FROM sewing
                    WHERE qty_out >= 0 
                    GROUP BY cmt_id, worksheet_id
                    ORDER BY date_in DESC, cmt_id, worksheet_id";

$result = $conn->query($sql);


if ($result->num_rows > 0) {
    // Create a new array to store the data for Excel export
    $excelData = array();

    $totalQty = 0;

    while ($row = $result->fetch_assoc()) {
        $article_id = fetchWorksheet($row['worksheet_id'])->fetch_assoc()['article_id'];
        $article = getArticleById($article_id);
        $cmtName = getCMTNameById($row['cmt_id']);

        $categoryName = getCategoryNameById($article['category_id']);

        $qtyCutting = getCuttingQtyByWorksheetId($row['worksheet_id']);
        $qtySisa = $row['qty_in'] - $row['qty_out'] - $row['qty_missing'];

        if ($qtySisa == 0) {
            continue;
        }

        // Organize the row data based on cmt_id
        if (!isset($cmtData[$row['cmt_id']])) {
            $cmtData[$row['cmt_id']] = array();
        }

        $cmtData[$row['cmt_id']][] = array(
            'CMT' => $cmtName,
            'TGL MASUK CMT' => $row['date_in'],               // B
            'NO WORKSHEET' => $row['worksheet_id'],           // C
            'NO ARTIKEL' => $article['article_id'],
            'MODEL' => $article['model_name'],
            'CATEGORY' => $categoryName,
            'QTY IN' => $row['qty_in'],
            'QTY OUT' => $row['qty_out'],                     // I
            'GAGAL' => $row['qty_fail'],                      // J
            'HILANG' => $row['qty_missing'],                  // K
            'SISA' => $qtySisa                               // L
        );

        $totalQty += $row['qty_out'];
    }

    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();

    // Set the active sheet
    $sheet = $spreadsheet->getActiveSheet();

    // Set the headers
    $headers = array_keys($cmtData[array_key_first($cmtData)][0]);
    $sheet->fromArray($headers, NULL, 'B4');

    // Set the font style for the headers to bold
    $headerStyle = $sheet->getStyle('B4:' . $sheet->getHighestColumn() . '4');
    $headerStyle->getFont()->setBold(true);

    $sheet->setCellValue('B1', "Laporan Sisa Barang di CMT");
    $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(18);
    $sheet->mergeCells('B1:L1'); // Merging cells from column B to G in row 1

    // Apply center alignment to the merged cell
    $style = $sheet->getStyle('B1:L1');
    $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


    $currentDate = date("Y-m-d");

    $sheet->setCellValue('B2', "CMT:");
    $sheet->setCellValue('C2', "All");
    $sheet->setCellValue('E2', "Tanggal Laporan: ");
    $sheet->setCellValue('F2', $currentDate);

    $sheet->getStyle('B2')->getFont()->setBold(true);
    $sheet->getStyle('E2')->getFont()->setBold(true);


    // Set the data from the Excel data array
    $row = 5;
    $totalQtyLabel = 0;
    $previousCmtId = null; // To keep track of the previous cmt_id

    foreach ($cmtData as $cmtId => $cmtRows) {
        if ($previousCmtId !== null && $previousCmtId !== $cmtId) {
            // Apply background color to the empty row
            $sheet->getStyle('B' . $row . ':' . $sheet->getHighestColumn() . $row)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFFFCC'); // Light orange color

            // Merge columns B to G for "Total: " text and apply borders
            $sheet->mergeCells('B' . $row . ':G' . $row);
            $sheet->getStyle('B' . $row . ':G' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // Add "Total: " text in column G of the empty row
            $sheet->setCellValue('B' . $row, "Total: ");
            $sheet->getStyle('B' . $row)->getFont()->setBold(true);

            // Align the "Total: " text to the right
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            // Calculate and display the total sum for columns H to L
            $column = 'H';
            while ($column <= 'L') {
                $totalSumFormula = '=SUM(' . $column . ($row - count($cmtData[$previousCmtId])) . ':' . $column . ($row - 1) . ')';

                $cell = $sheet->getCell($column . $row);
                $cell->setValue($totalSumFormula);
                $sheet->getStyle($column . $row)->getFont()->setBold(true);
                $sheet->getStyle($column . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $column++;
            }

            $row += 2; // Insert an empty row between different cmt_id groups
        }

        foreach ($cmtRows as $data) {
            $column = 'B';
            foreach ($data as $key => $value) {
                $sheet->setCellValue($column . $row, $value);

                // Apply normal (default) border to the cell
                $sheet->getStyle($column . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                if ($key === 'CMT') { // If the key is 'CMT', apply bold font style
                    $sheet->getStyle($column . $row)->getFont()->setBold(true);
                }

                if ($column == "I") {
                    $totalQtyLabel += $value;
                }

                $column++;
            }
            $row++;
            $previousCmtId = $cmtId; // Update the previous cmt_id
        }
    }

    // Add the empty row to show total for last cmt_id group
    if ($previousCmtId !== null) {
        // Apply background color to the empty row
        $sheet->getStyle('B' . $row . ':' . $sheet->getHighestColumn() . $row)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFCC'); // Light orange color

        // Merge columns B to G for "Total: " text and apply borders
        $sheet->mergeCells('B' . $row . ':G' . $row);
        $sheet->getStyle('B' . $row . ':G' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Add "Total: " text in column G of the empty row
        $sheet->setCellValue('B' . $row, "Total: ");
        $sheet->getStyle('B' . $row)->getFont()->setBold(true);

        // Align the "Total: " text to the right
        $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Calculate and display the total sum for columns H to L
        $column = 'H';
        while ($column <= 'L') {
            $totalSumFormula = '=SUM(' . $column . ($row - count($cmtData[$previousCmtId])) . ':' . $column . ($row - 1) . ')';
            $cell = $sheet->getCell($column . $row);
            $cell->setValue($totalSumFormula);
            $sheet->getStyle($column . $row)->getFont()->setBold(true);
            $sheet->getStyle($column . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $column++;
        }
    }

    $row += 2;

    $sheet->setCellValue("B".$row, "Dibuat oleh,");
    $sheet->setCellValue("F".$row, "Mengetahui,");
    $sheet->getStyle('B'.$row)->getFont()->setBold(true);
    $sheet->getStyle('F'.$row)->getFont()->setBold(true);


    $row += 4;

    include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/logging/get_user_information.php';

    $username = $_SESSION['username'];
    $userFullName = getUserFullnameByUsername($username);

    $sheet->setCellValue("B".$row, $userFullName);
    $sheet->setCellValue("F".$row, "Manager Produksi");

    // Define the RGB color code for light orange (255, 204, 153)
    $lightOrangeColor = 'FFCC99'; // ARGB format: AARRGGBB

    // Set the background color of cells B4 to F4 to light orange
    $sheet->getStyle('B4:L4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($lightOrangeColor);

    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                'color' => ['argb' => '000000'], // Black color
            ],
        ],
    ];

    $sheet->getStyle('B4:L4')->applyFromArray($styleArray);

    // Set the column width of B to G to autofit
    for ($col = 'B'; $col <= 'L'; $col++) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Create a new Excel file
    $writer = new Xlsx($spreadsheet);

    $currentDate = date('Ymd-His');





    $file_name = 'laporan_sisa_barang_di_cmt_'. $currentDate . '.xlsx'; // Change the file name as needed

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
