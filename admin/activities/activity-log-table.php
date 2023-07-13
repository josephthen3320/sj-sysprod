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
            <th class="w3-center">Activity ID</th>
            <th class="w3-center">User</th>
            <th class="w3-center">Type</th>
            <th class="w3-center">Details</th>
            <th class="w3-center">Timestamp</th>
        </tr>

        <?php
        // Include the database connection file
        require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";

        $conn = getConnUser();
        $log_conn = getConnLog();

        require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/logging/get_user_information.php';

        // Fetch all user information from the database
        $sql = "SELECT *
                        FROM user_activity_log ORDER BY timestamp DESC";

        $result = mysqli_query($log_conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {

                $aid = str_pad($row['id'], 4, '0', STR_PAD_LEFT);
                $user_fname = getUserFullnameByUsername(getUsernameById($row['user_id']));
                $activity_name = getActivityName($row['activity_id'], $log_conn);
                $activity_name = $activity_name == "" ? "???" : $activity_name;
                //$activity_detail = $row['activity_detail'];
                $activity_detail = implode("<br>", explode(";;", $row['activity_detail']));

                echo "<tr class='' style='cursor: ;'>";
                echo "<td class='w3-center' style='vertical-align: middle'>{$aid}</td>";
                echo "<td style='vertical-align: middle' class='''>{$user_fname}</td>";
                echo "<td style='vertical-align: middle' class='w3-center'>{$activity_name}</td>";
                echo "<td style='vertical-align: middle; width: 35%;' class=''>{$activity_detail}</td>";
                echo "<td style='vertical-align: middle' class=''>{$row['timestamp']}</td>";
                echo "</tr>";
            }
        }

        $conn->close();


        function getActivityName($a_id, $log_conn) {
            $a_sql = "SELECT activity FROM activities WHERE id = '$a_id'";
            $a_result = $log_conn->query($a_sql);

            if ($a_result->num_rows > 0) {
                $a_name = $a_result->fetch_assoc()['activity'];
            }
            else {
                $a_name = "";
            }


            return $a_name;
        }
        ?>

    </table>
</div>

<script>
    function loadArticleDetail(id) {
        // Send message to the parent window
        window.parent.postMessage({ type: "loadArticleDetail", id: id }, "*");
    }
</script>

</body>
</html>