
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/db.php';
$log_conn = getConnLog();
require_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/logging/get_user_information.php';

// Fetch all user information from the database
$sql = "SELECT * FROM user_activity_log ORDER BY `timestamp` DESC LIMIT 5";
$result = mysqli_query($log_conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $aid                = str_pad($row['id'], 4, '0', STR_PAD_LEFT);
        $user_fname         = getUserFullnameByUsername(getUsernameById($row['user_id']));
        $activity_name      = getActivityName($row['activity_id'], $log_conn);
        $activity_name      = $activity_name == "" ? "???" : $activity_name;
        $activity_detail  = $row['activity_detail'];
        $activity_format_detail    = implode("<br>", explode(";;", $activity_detail));


        $lcDetail = strtolower($activity_detail);

        if (strpos($lcDetail, 'failed') !== false) {
            $status = '<span class="w3-text-red">FAILED</span>';
        } elseif (strpos($lcDetail, 'success') !== false) {
            $status = '<span class="w3-text-green">OK</span>';
        } else {
            $status = '';
        }

        $timestamp          = $row['timestamp'];

        $bar_colour         = getColour($row['activity_id']);
        $icon               = getActivityIcon($row['activity_id']);


        echo "<div class='w3-bar-block w3-text-black w3-white'>
                <div class='w3-bar-item w3-leftbar w3-border-{$bar_colour} w3-container w3-hover-light-grey' style='padding: 16px 40px;'>
                    <div class='w3-quarter'>
                        $icon
                    </div>     
                    <div class='w3-quarter'>
                        <b>$activity_name $status</b>
                    </div> 
                    <div class='w3-quarter'>
                        <b>$user_fname</b>
                    </div> 
                    <div class='w3-quarter'>
                        <b>$timestamp</b>
                    </div>     
                                
                </div>
              </div>
            ";

/*
        echo "<div class=\"w3-bar-block w3-text-black w3-white\">";
        echo "<div class=\"w3-bar-item w3-leftbar w3-border-blue w3-hover-light-grey\" style=\"padding: 16px 40px;\">";
        echo "<div class='w3-container w3-cell w3-cell-middle w3-quarter'>";
        echo getActivityIcon($row['activity_id']);
        echo "</div>";
        echo "
        <div class='w3-container w3-cell w3-cell-middle w3-quarter'>
                <span>$activity_name</span>
                <span>$user_fname</span>
            </div>";
        echo "
        <div class='w3-container w3-cell w3-cell-middle'>
                <span>{$row['timestamp']}</span>
            </div>";
        //echo $aid . $user_fname . $activity_name . $activity_detail;
        echo "</div></div>";
*/

    }

}

function getColour ($x) {
    switch ($x) {
        case 11:
            $result = "blue";
            break;
        case 12:
            $result = "orange";
            break;
        default:
            $result = "black";
            break;
    }

    unset($x);
    return $result;
}

function getActivityIcon($x) {

    $c = getColour($x);

    $result = "<i class=\"fa-solid ";

    switch ($x) {
        case 11:
            $result .= "fa-right-to-bracket";
            break;
        case 12:
            $result .= "fa-right-from-bracket";
            break;
        default:
            $result .= "fa-circle-info";
            break;
    }

    $result .= " fa-2xl w3-text-{$c}\"></i >";

    unset($c);
    return $result;
}

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

<?php
/*
    <div class="w3-bar-block w3-text-black w3-white">
        <div class="w3-bar-item w3-leftbar w3-border-blue w3-hover-light-grey" style="padding: 16px 40px;">
            <div class="w3-container w3-cell w3-cell-middle">
                <i class="fa-solid fa-exclamation-circle fa-2xl w3-text-blue"></i>
            </div>
            <div class="w3-container w3-cell w3-cell-middle">
                <span>Sysprod Updated</span> <br>
                <span><b><?= date("Y.m.d") ?></b></span>
            </div>
        </div>
        <div class="w3-bar-item w3-leftbar w3-border-orange w3-hover-light-grey" style="padding: 16px 40px;">
            <div class="w3-container w3-cell w3-cell-middle">
                <i class="fa-solid fa-bug fa-2xl w3-text-orange"></i>
            </div>
            <div class="w3-container w3-cell w3-cell-middle">
                <span>New Ticket</span> <br>
                <span>
                                        <b><?= date("Y.m.d") ?></b>
                                    </span>
            </div>
        </div>
        <div class="w3-bar-item w3-leftbar w3-border-red w3-hover-light-grey" style="padding: 16px 40px;">
            <div class="w3-container w3-cell w3-cell-middle">
                <i class="fa-solid fa-face-frown-open fa-2xl w3-text-red"></i>
            </div>
            <div class="w3-container w3-cell w3-cell-middle">
                <span>Service Down: SysProd</span> <br>
                <span>
                                        <b><?= date("Y.m.d - H:i:s") ?></b>
                                    </span>
            </div>
        </div>
    </div>

*/