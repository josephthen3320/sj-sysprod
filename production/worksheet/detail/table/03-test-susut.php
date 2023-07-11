<!-- Test Susut Main Body Table -->
<div class="w3-bar" style="display: flex; justify-content: center;">
    <span class="w3-bar-item"><b>TEST SUSUT MAIN BODY</b></span>
</div>
<div class="w3-tiny">
    <table id="testTable" class="tg" style="width: 100%;" data-worksheet-id="<?= $worksheet_id ?>">
        <thead>
        <tr>
            <th class="tg-ncfi w3-tiny">KAIN</th>
            <th class="tg-ncfi w3-tiny">PANJANG</th>
            <th class="tg-ncfi w3-tiny">LEBAR</th>
            <th class="tg-ncfi w3-tiny w3-text-red">NOTES</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td contenteditable class="tg-0lax w3-tiny"></td>
            <td contenteditable class="tg-0lax w3-tiny"></td>
            <td contenteditable class="tg-0lax w3-tiny"></td>
            <td contenteditable class="tg-0lax w3-tiny"></td>
        </tr>
        <tr>
            <td contenteditable class="tg-0lax w3-tiny"></td>
            <td contenteditable class="tg-0lax w3-tiny"></td>
            <td contenteditable class="tg-0lax w3-tiny"></td>
            <td contenteditable class="tg-0lax w3-tiny"></td>
        </tr>
        <tr>
            <td contenteditable class="tg-0lax w3-tiny"></td>
            <td contenteditable class="tg-0lax w3-tiny"></td>
            <td contenteditable class="tg-0lax w3-tiny"></td>
            <td contenteditable class="tg-0lax w3-tiny"></td>
        </tr>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all the editable cells in the table
        const cells = document.querySelectorAll('#testTable tbody td[contenteditable]');

        // Loop through each cell and attach event listeners
        cells.forEach(function(cell) {
            cell.addEventListener('input', function() {
                const worksheetId = document.querySelector('#testTable').dataset.worksheetId;
                const rowIndex = this.parentNode.rowIndex - 1;
                const columnIndex = this.cellIndex;
                const value = this.textContent;

                // Send an AJAX request to check if the cell already has a value
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'table/php/03-submit.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Check the response to determine if the cell already has a value
                            const response = JSON.parse(xhr.responseText);
                            if (response.hasValue) {
                                // Cell already has a value, update the existing value in the table
                                document.querySelector(`#testTable tbody tr:nth-child(${rowIndex + 1}) td:nth-child(${columnIndex + 1})`).textContent = value;
                            } else {
                                // Cell is empty, insert the new value into the table
                                // ... insert the code to insert the value into the database here
                            }
                        } else {
                            // Request failed
                            console.error('Error:', xhr.status);
                        }
                    }
                };

                // Send the AJAX request with the cell information
                xhr.send(`worksheetId=${encodeURIComponent(worksheetId)}&rowIndex=${encodeURIComponent(rowIndex)}&columnIndex=${encodeURIComponent(columnIndex)}&value=${encodeURIComponent(value)}`);
            });
        });
    });

</script>

<?php include "php/03-fetch-content.php";