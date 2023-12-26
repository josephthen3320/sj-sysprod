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
</head>
<script src="/assets/js/utils.js"></script>
<body>
<div class="w3-container classification-content" id="worksheet-modal" style="">
    <table class="w3-table w3-table-all w3-hide-small w3-small">
        <thead>
        <tr>
            <th class="w3-center" style="vertical-align: middle;">No</th>
            <th class="w3-center" style="vertical-align: middle;">Worksheet No.</th>
            <th class="w3-center" style="vertical-align: middle;">Article ID</th>
            <th class="w3-center" style="vertical-align: middle;">Model</th>
            <?php if(!in_array($role, [2, 5, 6])): ?>
            <th class="w3-center" style="vertical-align: middle;">Subcategory</th>
            <?php endif; ?>

            <th class="w3-center" style="vertical-align: middle;">Worksheet Date.</th>
            <th class="w3-center" style="vertical-align: middle;">PO Date</th>

            <?php if($role != 2): ?>
            <th class="w3-center" style="vertical-align: middle;">Lebar Kain</th>
            <?php endif; ?>

            <!-- Admin Prod  -->
            <?php if(!in_array($role, [5,6])): ?>
            <th class="w3-center" style="vertical-align: middle;">Embro</th>
            <th class="w3-center" style="vertical-align: middle;">Print/<br>Sablon</th>
            <?php endif; ?>

            <!-- MD Prod  -->
            <?php if(in_array($role, [5, 6, 2])): ?>
            <th class="w3-center" style="vertical-align: middle;">Qty Est.</th>
            <?php endif;?>

            <?php if(in_array($role, [5, 6])): ?>
            <th class="w3-center" style="vertical-align: middle;">Merk</th>
            <?php endif; ?>

            <th class="w3-center" style="vertical-align: middle;">Washing</th>

            <th class="w3-center" style="vertical-align: middle;">Actions</th>
            <th class="w3-center" style="vertical-align: middle;">Send to</th>
            <th class="w3-center" style="vertical-align: middle;">Posisi</th>
        </tr>
        </thead>
        <tbody>
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

            echo "<tr>";
            echo "  <td style='vertical-align: middle;'>" . ($index + 1) . "</td>";
            echo "  <td style='vertical-align: middle;'>{$worksheet['worksheet_id']}</td>";

            echo "  <td style='vertical-align: middle;'>{$details['article_id']}</td>";
            echo "  <td style='vertical-align: middle;'>{$article['model_name']}</td>";

            if(!in_array($role, [2, 5, 6])):
            echo "  <td style='vertical-align: middle;'>{$subcategory}</td>";
            endif;

            echo "  <td style='vertical-align: middle;'>{$worksheet['worksheet_date']}</td>";
            echo "  <td style='vertical-align: middle;'>{$worksheet['po_date']}</td>";

            if(!in_array($role, [2])):
            echo "  <td class='w3-center' style='vertical-align: middle;'>{$details['cloth_width']}</td>";
            endif;

            // CMTs
            $article = getArticleById($articleId);
            $cmtEmbro = getCMTNameById($article['embro_cmt_id']);
            $cmtPrint = getCMTNameById($article['print_cmt_id']);
            $washes = implode("<br>", fetchWashNamesByArticleId($articleId));

            $brand = getBrandNameById($article['brand_id']);

            if(!in_array($role, [5, 6])):
                echo "  <td class='w3-center' style='vertical-align: middle;'>{$cmtEmbro}</td>";
                echo "  <td class='w3-center' style='vertical-align: middle;'>{$cmtPrint}</td>";
            endif;

            if (in_array($role, [5, 6, 2])):
                echo "  <td class='w3-center' style='vertical-align: middle;'>{$details['qty']}</td>";

                if(!in_array($role,[2])):
                echo "  <td class='w3-center' style='vertical-align: middle;'>{$brand}</td>";
                endif;
            endif;

            echo "  <td class='w3-center' style='vertical-align: middle;'>{$washes}</td>";

            $id = $details['id'];
            echo "  <td style='vertical-align: middle;'>";

            // feature disabled temporarily
            if ($role <= -1) {
                echo "<button class='w3-button w3-blue-gray' onclick='openPopupURL35(\"detail?id=" . $id . "\", \"wsdetail\")'><i class='fa-solid fa-fw fa-magnifying-glass'></i></button>";
            }

            // Export button
            if (in_array($role, [0,1,2,5,6])) {
                echo "<button class='w3-button w3-green' onclick='openPopupURL2(\"export.php?id=" . $id . "\", \"wspopup\")'><i class='fa-solid fa-fw fa-file-export'></i></button>";
            }

            // Delete button
            if (in_array($role, [0,1,2,5]) && getWorksheetPosition($worksheetId) == 0) {
                echo "<button class='w3-button w3-pale-red w3-text-red' onclick = 'openPopupURL2(\"delete?id=" . $id . "\", \"wspopup\")' ><i class='fa-solid fa-fw fa-trash' ></i ></button >";
            }
            echo "<br>";

            // Upload Button
			if (in_array($role, [0,1,2,5,6])) {
				$btnColour = "blue";
				if (isFileExists($worksheet['worksheet_id'].".xlsx")) {
					$btnColour = "indigo";
				}

				echo "<button class='w3-button w3-{$btnColour}' onclick='openPopupURL2(\"import.php?id=" . $id . "\", \"wspopup\")'><i class='fa-solid fa-fw fa-upload'></i></button>";
			}

            // Download Button
            if ($role >= -1) {
				if (isFileExists($worksheet['worksheet_id'].".xlsx")) {
					$filePath = "files/" . $worksheet['worksheet_id'] . ".xlsx";
					echo "<a class='w3-button w3-red' href='{$filePath}' id='wsDownloadLink'><i class='fa-solid fa-fw fa-download'></i></a>";
				}
            }
            echo "</td>";

            // Send to Button
            echo "<td style='vertical-align: middle;'>";
            if (in_array($role, [0,1,2,5,6])) {
                if (getWorksheetPosition($worksheetId) <= 0) {
                    echo "<button class='w3-button w3-red' onclick='openURL(\"send-to-polamarker.php?w=" . $worksheetId . "\")'>Pola Marker&nbsp;&nbsp;<i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
                } else {
                    echo "<button class='w3-button w3-hover-red w3-red w3-disabled'><i class=\"fa-solid fa-check\"></i></button>";
                }
            } else {
                if (getWorksheetPosition($worksheetId) > 0) {
                    echo "<button class='w3-button w3-hover-red w3-red w3-disabled'><i class=\"fa-solid fa-check\"></i></button>";
                }
            }
            echo "</td>";

            $pos = parseWorksheetPosition(getWorksheetPosition($worksheetId));
            $url = "/transaction/" . strtolower(str_replace(" ", "-", $pos));
            $url = str_replace("unknown", "", $url);    // remove link if unknown
            // Link to process view
            echo "<td style='vertical-align: middle;'><a href='$url' target='_blank'>{$pos}</a></td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>

    <!-- Mobile Table -->
    <table class="w3-table w3-table-all w3-hide-large w3-hide-medium">
        <?php
        foreach ($worksheets as $index => $worksheet) {
            $worksheetId        = $worksheet['worksheet_id'];
            $details = fetchWorksheetDetails($worksheetId);
            $articleId          = $details['article_id'];
            $worksheetDate      = $worksheet['worksheet_date'];
            $poDate             = $worksheet['po_date'];
            $position           = parseWorksheetPosition(getWorksheetPosition($worksheetId));
            ;
            
            echo "<thead>";
            echo "<tr class='w3-indigo'>";      // todo: change colour later
            echo "<th>Worksheet ID</th>";
            echo "<th>{$worksheetId}</th>";
            echo "</tr>";
            echo "</thead>";
            
            echo "<tbody>";
            echo "<tr>";
            echo "<td>Article ID.</td>";
            echo "<td>{$articleId}</td>";
            echo "</tr>";
            
            echo "<tr>";
            echo "<td>Worksheet Date</td>";
            echo "<td>{$worksheetDate}</td>";
            echo "</tr>";
            
            echo "<tr>";
            echo "<td>PO Date</td>";
            echo "<td>{$poDate}</td>";
            echo "</tr>";
            
            echo "<tr>";
            echo "<td>Position</td>";
            echo "<td>{$position}</td>";
            echo "</tr>";
            
            echo "<tr>";
            // Action buttons
            echo "<td class='w3-center'>";

            // todo: Proper role check

            //echo "<button class='w3-button w3-blue-gray' onclick='openPopupURL35(\"detail?id=" . $id . "\", \"wsdetail\")'><i class='fa-solid fa-magnifying-glass'></i></button>";

            echo "<button class='w3-button w3-green' onclick='openPopupURL(\"export.php?id=" . $id . "\", \"wsgenerate\")'><i class='fa-solid fa-file-export'></i></button>";

            if ($role <= 2) {
                echo "<button class='w3-button w3-red' onclick='openPopupURL(\"delete?id=" . $id . "\", \"wsdelete\")'><i class='fa-solid fa-trash'></i></button>";
            }

            echo "</td>";
            // Send button
            echo "<td class='w3-center'>";
            if (getWorksheetPosition($worksheetId) <= 0) {
                echo "<button style='width: 85%;' class='w3-button w3-red' onclick='openURL(\"send-to-polamarker.php?w=" . $worksheetId . "\")'><i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
            } else {
                echo "<button style='width: 85%;' class='w3-button w3-hover-red w3-red w3-disabled'><i class=\"fa-solid fa-check\"></i></button>";
            }
            echo "</td>";
            echo "</tr>";
            
            echo "<tr>";
            echo "<td colspan='2'></td>";
            echo "</tr>";
            echo "</tbody>";            
        }
        
        ?>
    </table>

</div>

</body>
</html>