<?php
session_start();
$uid = $_SESSION['user_id'];
$role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">

    <script type="module">
        import {
            Grid,
            html
        } from "https://unpkg.com/gridjs?module";
    </script>

    <script src="/assets/js/utils.js"></script>
</head>
<body>
<div class="w3-container classification-content" id="worksheet-modal" style="">
    <div id="table-container"></div>


</div>
</body>
</html>


<!-- Include Grid.js CSS -->
<link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
<!-- Include Grid.js JavaScript -->
<script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Define your table data as a JavaScript array
        var tableData = [
            <?php
            include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';
            include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
            include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet_position.php';

            $worksheets = fetchWorksheets();

            function isFileExists($filename) {
                $filePath = 'files/' . $filename; // Assuming the files are stored in the "files" directory
                return file_exists($filePath);
            }

            foreach ($worksheets as $index => $worksheet) {
                $worksheetId = $worksheet['worksheet_id'];
                $details = fetchWorksheetDetails($worksheetId);
                $articleId = $details['article_id'];
                $article = getArticleById($articleId);
                $subcategory = getSubcategoryNameById($article['subcategory_id']);
                $washes = implode("<br>", fetchWashNamesByArticleId($articleId));
                $brand = getBrandNameById($article['brand_id']);
                $cmtEmbro = getCMTNameById($article['embro_cmt_id']);
                $cmtPrint = getCMTNameById($article['print_cmt_id']);
                $id = $details['id'];
                $pos = parseWorksheetPosition(getWorksheetPosition($worksheetId));
                $url = "/transaction/" . strtolower(str_replace(" ", "-", $pos));
                $url = str_replace("unknown", "", $url); // remove link if unknown

                echo "[";
                echo ($index + 1) . ",";
                echo "'" . $worksheet['worksheet_id'] . "',";
                echo "'" . $details['article_id'] . "',";
                echo "'" . $article['model_name'] . "',";

                if (!in_array($role, [2, 5, 6])):
                    echo "'" . $subcategory . "',";
                endif;

                echo "'" . $worksheet['worksheet_date'] . "',";
                echo "'" . $worksheet['po_date'] . "',";

                if (!in_array($role, [2])):
                    echo "'" . $details['cloth_width'] . "',";
                endif;

                if (!in_array($role, [5, 6])):
                    echo "'" . $cmtEmbro . "',";
                    echo "'" . $cmtPrint . "',";
                endif;

                if (in_array($role, [5, 6, 2])):
                    echo "'" . $details['qty'] . "',";

                    if (!in_array($role, [2])):
                        echo "'" . $brand . "',";
                    endif;
                endif;

                echo "'" . $washes . "',";

                // Export button
                if (in_array($role, [0, 1, 2, 5, 6])) {
                    echo "'<button class=\"w3-button w3-green\" onclick=\"openPopupURL2(\\\"export.php?id=" . $id . "\\\", \\\"wspopup\\\")\"><i class=\"fa-solid fa-fw fa-file-export\"></i></button>',";
                } else {
                    echo "'',";
                }

                // Delete button
                if (in_array($role, [0, 1, 2, 5]) && getWorksheetPosition($worksheetId) == 0) {
                    echo "'<button class=\"w3-button w3-pale-red w3-text-red\" onclick=\"openPopupURL2(\\\"delete?id=" . $id . "\\\", \\\"wspopup\\\")\"><i class=\"fa-solid fa-fw fa-trash\"></i></button>',";
                } else {
                    echo "'',";
                }

                // Upload Button
                if (in_array($role, [0, 1, 2, 5, 6])) {
                    $btnColour = "blue";
                    if (isFileExists($worksheet['worksheet_id'] . ".xlsx")) {
                        $btnColour = "indigo";
                    }

                    echo "'<button class=\"w3-button w3-{$btnColour}\" onclick=\"openPopupURL2(\\\"import.php?id=" . $id . "\\\", \\\"wspopup\\\")\"><i class=\"fa-solid fa-fw fa-upload\"></i></button>',";
                } else {
                    echo "'',";
                }

                // Download Button
                if ($role >= -1) {
                    if (isFileExists($worksheet['worksheet_id'] . ".xlsx")) {
                        $filePath = "files/" . $worksheet['worksheet_id'] . ".xlsx";
                        echo "'<a class=\"w3-button w3-red\" href=\"{$filePath}\" id=\"wsDownloadLink\"><i class=\"fa-solid fa-fw fa-download\"></i></a>',";
                    } else {
                        echo "'',";
                    }
                } else {
                    echo "'',";
                }

                // Send to Button
                echo "'";

                if (in_array($role, [0, 1, 2, 5, 6])) {
                    if (getWorksheetPosition($worksheetId) <= 0) {
                        echo "<button class=\"w3-button w3-red\" onclick=\"openURL(\\\"send-to-polamarker.php?w=" . $worksheetId . "\\\")\">Pola Marker&nbsp;&nbsp;<i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
                    } else {
                        echo "<button class=\"w3-button w3-hover-red w3-red w3-disabled\"><i class=\"fa-solid fa-check\"></i></button>";
                    }
                } else {
                    if (getWorksheetPosition($worksheetId) > 0) {
                        echo "<button class=\"w3-button w3-hover-red w3-red w3-disabled\"><i class=\"fa-solid fa-check\"></i></button>";
                    }
                }

                echo "',";

                // Link to process view
                echo "'<a href=\"{$url}\" target=\"_blank\">{$pos}</a>'";
                echo "],";
            }
            ?>
        ];

        // Create a new Grid.js instance
        const grid = new gridjs.Grid({
            columns: [
                'No',
                'Worksheet No.',
                'Article ID',
                'Model',
                <?php if(!in_array($role, [2, 5, 6])): ?> 'Subcategory', <?php endif; ?>
                'Worksheet Date.',
                'PO Date',
                <?php if($role != 2): ?> 'Lebar Kain', <?php endif; ?>
                <?php if(!in_array($role, [5,6])): ?> 'Embro', 'Print/Sablon', <?php endif; ?>
                <?php if(in_array($role, [5, 6, 2])): ?> 'Qty Est.', <?php endif; ?>
                <?php if(in_array($role, [5, 6])): ?> 'Merk', <?php endif; ?>
                'Washing',
                'Actions',
                'Send to',
                'Posisi'
            ],
            data: tableData.map(row => {
                // Use formatter to format cell data with HTML
                return [
                    row[0], // No
                    row[1], // Worksheet No.
                    row[2], // Article ID
                    row[3], // Model
                    <?php if(!in_array($role, [2, 5, 6])): ?> row[4], <?php endif; ?> // Subcategory
                    row[5], // Worksheet Date
                    row[6], // PO Date
                    <?php if($role != 2): ?> row[7], <?php endif; ?> // Lebar Kain
                    <?php if(!in_array($role, [5,6])): ?> row[8], row[9], <?php endif; ?> // Embro and Print/Sablon
                    <?php if(in_array($role, [5, 6, 2])): ?> row[10], <?php endif; ?> // Qty Est.
                    <?php if(in_array($role, [5, 6])): ?> row[11], <?php endif; ?> // Merk
                    row[12], // Washing
                    {
                        // Format the Actions cell with buttons
                        formatter: (cell) => {
                        const buttons = cell.split(',').filter(Boolean); // Split and filter out empty strings
        return buttons.map(button => eval(button)).join(' '); // Execute and join the buttons
    }
    },
        row[14], // Send to
            row[15] // Posisi
    ];
    }),
    });

        // Render the grid in the 'table-container' div
        grid.render(document.getElementById('table-container'));
    });
</script>