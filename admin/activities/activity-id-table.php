<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Table</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>
<script src="/assets/js/utils.js"></script>
<body>
<div class="w3-container classification-content" id="worksheet-modal" style="">

    <table class="w3-table w3-table-all">
        <tr class="">
            <th class="w3-center">ID</th>
            <th class="w3-center">Activity</th>
        </tr>

        <?php
        // Include the database connection file
        require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";

        $conn = getConnUser();
        $log_conn = getConnLog();

        require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/logging/get_user_information.php';

        // Fetch all user information from the database
        $sql = "SELECT *
                        FROM activities WHERE id > 0 ORDER BY id ASC";

        $result = mysqli_query($log_conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {

                echo "<tr class='' style='cursor: ;'>";
                echo "<td class='w3-center' style='vertical-align: middle'>{$row['id']}</td>";
                echo "<td style='vertical-align: middle' class='''>{$row['activity']}</td>";
                echo "</tr>";
            }
        }

        $conn->close();

        ?>

    </table>
</div>

</body>
</html>