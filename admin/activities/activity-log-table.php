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
<div class="w3-container" id="" style="">

    <div class="w3-row w3-border w3-small">
        <div class="w3-col w3-center l1 m1" style="font-weight: bold">ID</div>
        <div class="w3-col w3-center l1 m1" style="font-weight: bold">User</div>
        <div class="w3-col w3-center l2 m2" style="font-weight: bold">Type</div>
        <div class="w3-col w3-center l4 m4" style="font-weight: bold">Details</div>
        <div class="w3-col w3-center l2 m2" style="font-weight: bold">IP Addr</div>
        <div class="w3-col w3-center l2 m2" style="font-weight: bold">Timestamp</div>
    </div>
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
                echo "<div class='w3-row w3-padding-16 w3-border w3-small'>";
                $aid = str_pad($row['id'], 4, '0', STR_PAD_LEFT);
                $user_fname = getUserFullnameByUsername(getUsernameById($row['user_id']));
                $activity_name = getActivityName($row['activity_id'], $log_conn);
                $activity_name = $activity_name == "" ? "???" : $activity_name;
                //$activity_detail = $row['activity_detail'];
                $activity_detail = implode("<br>", explode(";;", $row['activity_detail']));
                $ipaddress = $row['ip_address'];

                echo "<div class='w3-col l1 m1 w3-center'>{$aid}</div>";
                echo "<div class='w3-col l1 m1 w3-center'>{$user_fname}</div>";
                echo "<div class='w3-col l2 m2 w3-center'>{$activity_name}</div>";
                echo "<div class='w3-col l4 m4'>{$activity_detail}</div>";
                echo "<div class='w3-col l2 m2 w3-center'>{$ipaddress}</div>";
                echo "<div class='w3-col l2 m2 w3-center'>{$row['timestamp']}</div>";

                echo "</div>";
            }
        }

        ?>

    <table class="w3-table w3-table-all">
        <tr class="">
            <th class="w3-center" style="width: 5%;">Activity ID</th>
            <th class="w3-center" style="width: 10%;">User</th>
            <th class="w3-center" style="width: 20%;">Type</th>
            <th class="w3-center" style="width: 30%;">Details</th>
            <th class="w3-center" style="width: 15%;">Timestamp</th>
        </tr>

        <?php
        /*
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
                $ipaddress = $row['ip_address'];

                echo "<tr class='' style='cursor: ;'>";
                echo "<td class='w3-center' style='vertical-align: middle'>{$aid}</td>";
                echo "<td style='vertical-align: middle' class=''>{$user_fname}</td>";
                echo "<td style='vertical-align: middle' class='w3-center'>{$activity_name}</td>";
                echo "<td style='vertical-align: middle' class=''>{$activity_detail}</td>";
                echo "<td style='vertical-align: middle' class=''>{$row['timestamp']}</td>";
                echo "</tr>";
            }
        }

        $conn->close();
        */


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