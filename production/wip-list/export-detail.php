<?php
// Include the PhpSpreadsheet classes
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet_position.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_transaction.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_articles.php";
$conn = getConnProduction();

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data from the database
//$sql = "SELECT * FROM cutting WHERE date_cut IS NOT null ORDER BY date_cut DESC";
/*
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit();
}
*/

$sql = "SELECT * FROM worksheet 
                LEFT JOIN subm6595_sj_transaction.position AS p ON worksheet.worksheet_id = p.worksheet_id 
                INNER JOIN worksheet_detail ON worksheet.worksheet_id = worksheet_detail.worksheet_id 
                LEFT JOIN article ON worksheet_detail.article_id = article.article_id
                ORDER BY p.position_id ASC, p.worksheet_id DESC";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    // Create a new array to store the data for Excel export
    $excelData = array();

    $totalQty = 0;

    $index = 0;

    while ($worksheet = $result->fetch_assoc()) {
        ++$index;

        $worksheetId = $worksheet['worksheet_id'];
        $details = fetchWorksheetDetails($worksheetId);

        $qtyEstimated = getQtyEstimated($worksheetId);

        $qtyCutting = getQtyCutting($worksheetId);

        $qtySewingIn    = getQtySewingIn($worksheetId);
        $qtySewingOut   = getQtySewing($worksheetId);

        $qtyFinishingIn    = getQtyFinishing($worksheetId, 'in');
        $qtyFinishingOut   = getQtyFinishing($worksheetId, 'out');

        $qtyFinishingIn     = $qtyFinishingIn == 0 ? '-' : $qtyFinishingIn;

        $qtyQCFinalIn    = getQtyQCFinal($worksheetId, 'in');
        $qtyQCFinalOut   = getQtyQCFinal($worksheetId, 'out');

        $qtyQCFinalIn     = $qtyQCFinalIn == 0 ? '-' : $qtyQCFinalIn;

        $qtyGudang = getQtyGudangIn($worksheetId);

        $qtyGagal  = getQtyGagal($worksheetId);
        $qtyCacat  = getQtyCacat($worksheetId);
        $qtyHilang = getQtyHilang($worksheetId);

        $brand = getBrandNameById($worksheet['brand_id']);
        $category = getCategoryByArticleId($worksheet['article_id']);
        $subcategory = getSubcategoryByArticleId($worksheet['article_id']);

        $pos = parseWorksheetPosition(getWorksheetPosition($worksheetId));
        if ($pos == 'SEWING') {
            $cmt_sewing = getSewingCMTByWorksheetId($worksheet['worksheet_id']);
        } else {
            $cmt_sewing = '-';
        }

        // Add the row data to the Excel data array
        $excelData[] = array(
            '#' => $index,
            'No. Worksheet'     => $worksheetId,
            'No. ART'           => $worksheet['article_id'],
            'Model'             => $worksheet["model_name"],
            'Tgl. Worksheet'    => $worksheet['worksheet_date'],
            'Merk'              => $brand,
            'Kategori'          => $category,
            'Sub-Kategori'      => $subcategory,
            'Posisi'            => $pos,
            'CMT'               => $cmt_sewing,
            'Qty Est.'          => $qtyEstimated,
            'Qty Cutting'       => $qtyCutting,
            'Qty Sewing In'     => $qtySewingIn,
            'Qty Sewing Out'    => $qtySewingOut,
            'Qty Finishing In'  => $qtyFinishingIn,
            'Qty Finishing Out' => $qtyFinishingOut,
            'Qty QC Final In'   => $qtyQCFinalIn,
            'Qty QC Final Out'  => $qtyQCFinalOut,
            'Qty Gagal'         => $qtyGagal,
            'Qty Hilang'        => $qtyHilang,
            'Qty Cacat'         => $qtyCacat,
            'Qty Masuk Gudang'  => $qtyGudang

        );

        // $totalQty += $row['qty_out'];
    }

    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();

    // Set the active sheet
    $sheet = $spreadsheet->getActiveSheet();

    // Set the headers
    $headers = array_keys($excelData[0]);
    $sheet->fromArray($headers, NULL, 'B3');

    // Set the font style for the headers to bold
    $headerStyle = $sheet->getStyle('B3:' . $sheet->getHighestColumn() . '3');
    $headerStyle->getFont()->setBold(true);

    $today = date("d F Y");
    $sheet->setCellValue('B1', "LAPORAN STATUS WIP | PER TANGGAL: " . $today);
    $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(18);
    $sheet->mergeCells('B1:V1'); // Merging cells from column B to V in row 1

    // Set the data from the Excel data array
    $row = 4;
    $totalQtyLabel = 0;
    foreach ($excelData as $data) {
        $column = 'B';
        foreach ($data as $key => $value) {
            $cellCoordinate = $column . $row;

            // Set center alignment for cells with headers "Qty Est." to "Qty Masuk Gudang"
            if (strpos($key, 'Qty') !== false || $key === '#') {
                $sheet->getStyle($cellCoordinate)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }

            $sheet->setCellValue($cellCoordinate, $value);

            // Apply normal (default) border to the cell
            $sheet->getStyle($cellCoordinate)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $column++;
        }
        $row++;
    }

    // Define the RGB color code for light orange (255, 204, 153)
    $lightOrangeColor = 'FFCC99'; // ARGB format: AARRGGBB

    // Set the background color of cells B4 to F4 to light orange
    $sheet->getStyle('B3:V3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($lightOrangeColor);

    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                'color' => ['argb' => '000000'], // Black color
            ],
        ],
    ];

    $sheet->getStyle('B3:V3')->applyFromArray($styleArray);

    // Set the column width of B to G to autofit
    for ($col = 'B'; $col <= 'V'; $col++) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Create a new Excel file
    $writer = new Xlsx($spreadsheet);

    $currentDate = date('Ymd-His');



    $file_name = 'laporan_status_wip_'. $currentDate . '.xlsx'; // Change the file name as needed

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


/**********************************************/


function getQtyEstimated($wid) {
    $conn = getConnProduction();

    $sql = "SELECT qty FROM worksheet_detail WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty'];
}

function getQtyCutting($wid) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_out FROM cutting WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty_out'];
}

function getQtySewing($wid) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_out FROM sewing WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty_out'];
}

function getQtySewingIn($wid) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_in FROM sewing WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty_in'];
}

function getQtyFinishing($wid, $gateway) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_{$gateway} AS qty FROM finishing WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }
    return $result->fetch_assoc()['qty'];
}

function getQtyQCFinal($wid, $gateway) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_{$gateway} AS qty FROM qc_final WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }
    return $result->fetch_assoc()['qty'];
}

function getQtyGudangIn($wid) {
    $conn = getConnTransaction();

    $sql = "SELECT qty FROM warehouse WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty'];
}

function getQtyGagal($wid) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_fail AS qty FROM qc_final WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty'];
}

function getQtyHilang($wid) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_missing AS qty FROM qc_final WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty'];
}

function getQtyCacat($wid) {
    $conn = getConnTransaction();

    $sql = "SELECT qty_defect AS qty FROM qc_final WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);

    if ($result->num_rows != 1) {
        return "-";
    }

    return $result->fetch_assoc()['qty'];
}

function getSewingCMTByWorksheetId($wid) {
    $conn = getConnTransaction();
    $sql = "SELECT cmt_id FROM sewing WHERE worksheet_id = '$wid'";
    $result = $conn->query($sql);
    $cmt_id = $result->fetch_assoc()['cmt_id'];
    $conn->close();

    $conn = getConnProduction();
    $sql = "SELECT cmt_name FROM cmt WHERE cmt_id = '$cmt_id'";
    $result = $conn->query($sql);
    $cmt_name = $result->fetch_assoc()['cmt_name'];

    return $cmt_name;
}

function getCategoryByArticleId($aid) {
    $conn = getConnProduction();

    $sql = "SELECT category.category_name 
            FROM article
            INNER JOIN main_category AS category ON article.category_id = category.category_id
            WHERE article_id = '$aid'";
    $result = $conn->query($sql);

    return $result->fetch_assoc()['category_name'];
}

function getSubcategoryByArticleId($aid) {
    $conn = getConnProduction();

    $sql = "SELECT subcategory.subcategory_name 
            FROM article
            INNER JOIN subcategory ON article.subcategory_id = subcategory.subcategory_id
            WHERE article_id = '$aid'";
    $result = $conn->query($sql);

    return $result->fetch_assoc()['subcategory_name'];
}
