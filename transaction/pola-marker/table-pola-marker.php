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


<table class="w3-table w3-table-all w3-hide-small w3-margin-bottom" id="myTable">
    <thead>
    <tr>
        <th class="w3-center">Check</th>
        <th class="w3-center">No</th>
        <th class="w3-center">Pola Marker No.</th>
        <th class="w3-center">Worksheet No.</th>
        <th class="w3-center">Article No.</th>
        <th class="w3-center">Start Date</th>
        <th class="w3-center">End Date</th>
        <th class="w3-center">Surat Jalan</th>
        <th class="w3-center">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet_position.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_transaction.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_surat_jalan.php";

    $ct_data = fetchAllTransactionByProcess('pola_marker');
    $i = 0;

    while ($ct = $ct_data->fetch_assoc()) {
        ++$i;

        $worksheet = fetchWorksheet($ct['worksheet_id'])->fetch_assoc();
        $article_id = $worksheet['article_id'];

        echo "<tr>";
        echo "<td class=\"w3-center\">";
        $disabled = 'disabled';
        if (checkSuratJalanExistsByTransactionId($ct['pola_marker_id'])) {
            $disabled = "";
        }
        echo "<input class='w3-check' type='checkbox' $disabled>";
        echo "</td>";
        echo "<td class='w3-center'>{$i}</td>";
        echo "<td class='w3-center'>{$ct['pola_marker_id']}</td>";
        echo "<td class='w3-center'>{$ct['worksheet_id']}</td>";
        echo "<td class='w3-center'>{$article_id}</td>";
        echo "<td class='w3-center'>{$ct['date_in']}</td>";
        echo "<td class='w3-center'>{$ct['date_out']}</td>";

        echo "<td class='w3-center'>";
        if (checkSuratJalanExistsByTransactionId($ct['pola_marker_id'])) {
            $urlSuratJalan = "/transaction/surat-jalan/?i={$ct['sj_id']}&t={$ct['pola_marker_id']}&w={$ct['worksheet_id']}";
            echo "<button class='w3-button w3-green' onclick='openPopupURL2(\"$urlSuratJalan\")'><i class='fas fa-print'></i></button>";
        }

        echo "</td>";


        echo "<td class='w3-center'>";
        if (getWorksheetPosition($ct['worksheet_id']) <= 1) {
            echo "<button class='w3-button w3-red' onclick='openPopupURL2(\"sendDialog.php?w={$ct['worksheet_id']}&i={$ct['id']}&pi={$ct['pola_marker_id']}&a={$article_id}\", \"sendtocutting\", 500, 400)'><i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";

        } else {
            echo "<button class='w3-button w3-hover-red w3-red w3-disabled'><i class=\"fa-solid fa-check\"></i></button>";
        }
        echo "</td>";

        echo "</tr>";

    }


    ?>

    </tbody>
</table>

<button id="submitButton" class="w3-button w3-green w3-hide-small" style="margin-left: 32px;">Create Selected SJ</button>

<table class="w3-table-all w3-margin-top">
    <tr>
        <th>No.</th>
        <th>Surat Jalan ID</th>
        <th>Articles</th>
        <th>Actions</th>
    </tr>
    <?php
    $conn = getConnTransaction();
    $sql = "SELECT surat_jalan_id FROM surat_jalan WHERE type = '9'";
    $result = $conn->query($sql);
    $ii = 0;
    while($row = $result->fetch_assoc()) {
        echo "<tr>";

        echo "<td>" . ++$ii . "</td>";

        echo "<td>";
        echo $rSJID = $row['surat_jalan_id'];
        echo "<td>";
        $thisData = fetchSuratJalanMultiData($row['surat_jalan_id']);
        while ($row2 = $thisData->fetch_assoc()) {
            echo $row2['article_id'] . "<br>";
        }
        echo "<td>";
        echo "<button class='w3-button w3-green' onclick='openPopupURL2(\"/transaction/surat-jalan/search.php?id={$rSJID}\")'><i class='fas fa-print'></i></button>";
        echo "</td>";

        echo "</tr>";
    }

    ?>
</table>

<!-- Mobile Table -->
<table class="w3-table w3-table-all w3-hide-large w3-hide-medium">
    <?php
    foreach ($ct_data as $index => $ct) {
        $worksheetId = $ct['worksheet_id'];
        $pmId = $ct['pola_marker_id'];
        $details = fetchWorksheetDetails($worksheetId);
        $articleId = $details['article_id'];



        echo "<thead>";
        echo "<tr class='w3-indigo'>";      // todo: change colour later
        echo "<th>Pola Marker ID</th>";
        echo "<th>{$pmId}</th>";
        echo "</tr>";
        echo "</thead>";

        echo "<tbody>";
        echo "<tr>";
        echo "<td>Worksheet ID.</td>";
        echo "<td>{$worksheetId}</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td>Article ID.</td>";
        echo "<td>{$articleId}</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td>Start Date</td>";
        echo "<td>{$ct['date_in']}</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td>End Date</td>";
        echo "<td>{$ct['date_out']}</td>";
        echo "</tr>";

        echo "<tr>";
        // Action buttons
        echo "<td class='w3-center'>";
        if (checkSuratJalanExistsByTransactionId($ct['pola_marker_id'])) {
            $urlSuratJalan = "/transaction/surat-jalan/?i={$ct['sj_id']}&t={$ct['pola_marker_id']}&w={$ct['worksheet_id']}";
            echo "<button style='width: 85%;' class='w3-button w3-green' onclick='openPopupURL2(\"$urlSuratJalan\")'><i class='fas fa-print'></i></button>";
        }
        echo "</td>";
        // Send button
        echo "<td class='w3-center'>";
        if (getWorksheetPosition($worksheetId) == 1) {
            echo "<button style='width: 85%;' class='w3-button w3-red' onclick='openPopupURL2(\"sendDialog.php?w={$ct['worksheet_id']}&i={$ct['id']}&pi={$ct['pola_marker_id']}&a={$article_id}\", \"sendtocutting\", 500, 400)'><i class=\"fa-solid fa-arrow-right-from-arc\"></i></button>";
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

</body>
</html>


<script>
    document.getElementById("submitButton").addEventListener("click", function() {
        var selectedRows = [];
        var iDestination = 2;
        var iSource = 1;

        // Get all the table rows
        var rows = document.getElementById("myTable").rows;

        // Iterate over each row
        for (var i = 0; i < rows.length; i++) {
            var checkboxCell = rows[i].cells[0]; // Assuming the first cell contains a checkbox

            // Check if the checkbox is checked
            if (checkboxCell.firstChild.checked) {
                var rowData = {};

                // Get the worksheet ID from the second cell of the selected row
                var worksheetIdCell = rows[i].cells[3];
                var articleIdCell = rows[i].cells[4];
                var transactionIdCell = rows[i].cells[2];

                var worksheetId = worksheetIdCell.textContent.trim();
                var articleId = articleIdCell.textContent.trim();
                var transactionId = transactionIdCell.textContent.trim();

                var qty = 1;

                // Add the worksheet ID and article ID to the rowData object
                rowData.worksheetId = worksheetId;
                rowData.articleId = articleId;
                rowData.qty = qty;
                rowData.transactionId = transactionId;

                // Add the rowData object to the selectedRows array
                selectedRows.push(rowData);

                // Highlight the selected row
                rows[i].classList.add("w3-pale-yellow");
            } else {
                // Remove the highlight from the unselected row
                rows[i].classList.remove("w3-pale-yellow");
            }
        }

        // Log the selected rows array for debugging
        console.log(selectedRows);

        // Open the /transaction/surat_jalan/multi.php page in a new window
        var form = document.createElement("form");
        form.method = "post";
        form.action = "/transaction/surat-jalan/multi.php";
        form.target = "_blank";

        // Create a hidden input field to hold the selectedRows array as JSON
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "selectedRows";
        input.value = JSON.stringify(selectedRows);

        var destination = document.createElement("input");
        destination.type = "hidden";
        destination.name = "destination";
        destination.value = JSON.stringify(iDestination);

        var source = document.createElement("input");
        source.type = "hidden";
        source.name = "source";
        source.value = JSON.stringify(iSource);

        var title = document.createElement("input");
        title.type = "hidden";
        title.name = "title";
        title.value = JSON.stringify("Surat Jalan");

        // Append the input field to the form
        form.appendChild(input);
        form.appendChild(destination);
        form.appendChild(source);
        form.appendChild(title);

        // Submit the form to open the new window
        document.body.appendChild(form);
        form.submit();

        // Clean up: remove the form from the DOM
        document.body.removeChild(form);
    });
</script>