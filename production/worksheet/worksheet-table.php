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
    <table class="w3-table w3-table-all w3-hide-small">
        <thead>
        <tr>
            <th>No</th>
            <th>Worksheet No.</th>
            <th>Article ID.</th>
            <th>Worksheet Date.</th>
            <th>PO Date</th>
            <th>Actions</th>
            <th>Send to</th>
            <th>Current Position</th>
        </tr>
        </thead>
        <tbody>
        <?php
        include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';
        include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet_position.php';

        $worksheets = fetchWorksheets();

		function isFileExists($filename) {
			$filePath = 'files/' . $filename; // Assuming the files are stored in the "files" directory
			
			return file_exists($filePath);
		}

        foreach ($worksheets as $index => $worksheet) {
            $worksheetId = $worksheet['worksheet_id'];
            $details = fetchWorksheetDetails($worksheetId);

            echo "<tr>";
            echo "  <td>" . ($index + 1) . "</td>";
            echo "  <td>{$worksheet['worksheet_id']}</td>";

            echo "  <td>{$details['article_id']}</td>";
            echo "  <td>{$worksheet['worksheet_date']}</td>";
            echo "  <td>{$worksheet['po_date']}</td>";

            $id = $details['id'];
            echo "  <td>";

            // feature disabled temporarily
            if ($role <= -1) {
                echo "<button class='w3-button w3-blue-gray' onclick='openPopupURL35(\"detail?id=" . $id . "\", \"wsdetail\")'><i class='fa-solid fa-magnifying-glass'></i></button>";
            }
			
			if (in_array($role, [0, 1])) {
				$btnColour = "orange";
				if (isFileExists($worksheet['worksheet_id'].".xlsx")) { 
					$btnColour = "blue";
				}

				echo "<button class='w3-button w3-{$btnColour}' onclick='openPopupURL(\"import.php?id=" . $id . "\", \"wspopup\")'><i class='fa-solid fa-upload'></i></button>";
			}
				
            if (in_array($role, [0,1,2,5])) {
				if (isFileExists($worksheet['worksheet_id'].".xlsx")) { 
					$filePath = "files/" . $worksheet['worksheet_id'] . ".xlsx";
					echo "<a class='w3-button w3-red' href='{$filePath}' id='wsDownloadLink'><i class='fa-solid fa-download'></i></a>";
					//echo "<button class='w3-button w3-red' onclick='//todo: download the file uploaded'><i class='fa-solid fa-download'></i></button>";				
				}
            }
				
            if (in_array($role, [0,1,2,5])) {
                echo "<button class='w3-button w3-green' onclick='openPopupURL(\"export.php?id=" . $id . "\", \"wspopup\")'><i class='fa-solid fa-file-export'></i></button>";
            }
			
            if (in_array($role, [0,1,2,5])) {
                echo "<button class='w3-button w3-red' onclick = 'openPopupURL(\"delete?id=" . $id . "\", \"wspopup\")' ><i class='fa-solid fa-trash' ></i ></button >";
                }
            echo "</td>";


            echo "<td>";
            if (getWorksheetPosition($worksheetId) <= 0) {
                echo "<button class='w3-button w3-red' onclick='openURL(\"send-to-polamarker.php?w=" . $worksheetId . "\")'>Pola Marker&nbsp;&nbsp;<i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
            } else {
                echo "<button class='w3-button w3-hover-red w3-red w3-disabled'>Pola Marker&nbsp;&nbsp;<i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
            }
            echo "</td>";

            $pos = parseWorksheetPosition(getWorksheetPosition($worksheetId));
            $url = "/transaction/" . strtolower(str_replace(" ", "-", $pos));
            $url = str_replace("unknown", "", $url);    // remove link if unknown
            // Link to process view
            echo "<td><a href='$url' target='_blank'>{$pos}</a></td>";
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