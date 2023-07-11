<?php
session_start();

$page_title = "Ticketing";

// TODO: Change this to actual user role
$user_role = "Kucing Admin";

// Check if the user is not logged in, redirect to login page
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
$conn = getConnUser();

// MySQL query to fetch information from "users" table for the logged-in user
$username = $_SESSION["username"];
$sql = "SELECT first_name, last_name, employee_id FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, fetch the name and employee_id
    $row = $result->fetch_assoc();
    $name = $row["first_name"] . " " . $row["last_name"];
    $employeeId = $row["employee_id"];
} else {
    // User not found, handle the error
    header("Location: login.php");
}

$top_title = "Ticketing System";
if ($username == "nara") {
    $top_title .= "";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $top_title ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">

    <style>
        body {
            background-color: #F4EDE9;
        }
        .jt-orange {
            background-color: #ff5722;
        }
        .fa-4xl {
            font-size: 3em;
            line-height: 0.01637em;
            vertical-align: -0.27679em;
        }

        td {
            height: 80px;
            vertical-align: middle !important;
            /*cursor: default;*/
        }
    </style>
</head>
<body>

<!-- Left bar -->
<?= include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/sidebar.php" ; ?>

<div class="w3-threequarter w3-white" style="min-height: 100vh; margin-left: 25%;">
    <?= include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div style="margin-top: 16px; min-height: 100vh;">
        <!-- Ticketing Menu -->
        <div id="ticketing" class="w3-bar w3-white w3-border-bottom" style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>All Tickets</b></span>
        </div>

        <div class="w3-container w3-padding-16" style="background-color: #fbfbfb">
            <div class="w3-bar w3-right">
                <div class="w3-right w3-bar-item w3-container">
                    <label>Sort by: </label>
                    <select class="w3-light-grey">
                        <option selected value="date_asc">Date created (oldest)</option>
                        <option value="date_asc">Date created (newest)</option>
                        <option >Priority (highest)</option>
                        <option >Priority (lowest)</option>
                    </select>
                </div>
            </div>

            <table class="w3-table w3-table-all">
                <tr class="w3-small">
                    <th>ID</th>
                    <th>Requester Name</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Assignee</th>
                    <th>Create Date</th>
                </tr>

                <?php
                // Include the database connection file
                require_once 'php/db-its.php';

                // Fetch all tickets from the database
                $sql = "SELECT * FROM ticket ORDER BY FIELD(status, 'open', 'in-progress', 'pending', 'closed')";
                $result = mysqli_query($conn, $sql);

                // Check if any tickets are available
                if (mysqli_num_rows($result) > 0) {
                    // Display the tickets in a table
                    // Loop through each ticket and display its details
                    while ($row = mysqli_fetch_assoc($result)) {

                        $status = $row['status'];
                        switch ($status) {
                            case "open":
                            case "Open":
                                $status = "<span class='w3-text-blue-grey' style='font-weight: bold;'><i class=\"fa-solid fa-circle-play\"></i>&nbsp; Open</span>";
                                break;
                            case "closed":
                            case "Closed":
                                $status = "<span class='w3-text-green' style='font-weight: bold;'><i class=\"fa-solid fa-circle-stop\"></i>&nbsp; Closed</span>";
                                break;
                            case "pending":
                            case "Pending":
                                $status = "<span class='' style='font-weight: bold; color: darkred;'><i class=\"fa-solid fa-circle-pause\"></i>&nbsp; Pending</span>";
                                break;
                            case "inprogress":
                            case "in-progress":
                            case "In-Progress":
                                $status = "<span class='' style='font-weight: bold; color: orangered;'><i class=\"fa-solid fa-circle-right\"></i>&nbsp; In-progress</span>";
                                break;
                            default:
                                $status = "<span class='w3-text-grey' style='font-weight: bold;'><i class=\"fa-solid fa-circle-question\"></i>&nbsp; Unknown</span>";
                                break;
                        }


                        echo "<tr class='w3-hover-blue-grey' style='cursor: pointer;' onclick='promptFunction(\"{$row['id']}\", \"{$row['subject']}\")'>";
                        echo "<td>#{$row['id']}</td>";
                        echo "<td>                        
                                <img class=\"w3-circle\" src=\"/img/profile-placeholder.png\" width=\"40px\"> &nbsp;&nbsp;
                                {$row['requester_name']}
                              </td>";
                        echo "<td>{$row['subject']}</td>";
                        echo "<td>{$status}";
                        /*
                        echo "<form action='php/update_status.php' method='POST'>";
                        echo "<input type='hidden' name='ticket_id' value='{$row['id']}'>";
                        echo "<select name='status' onchange='this.form.submit()'>";
                        echo "<option value='Open' " . ($row['status'] == 'Open' ? 'selected' : '') . ">Open</option>";
                        echo "<option value='Closed' " . ($row['status'] == 'Closed' ? 'selected' : '') . ">Closed</option>";
                        echo "<option value='In Progress' " . ($row['status'] == 'In Progress' ? 'selected' : '') . ">In Progress</option>";
                        echo "</select>";
                        echo "</form>";
                        echo "</td>";
                        */
                        /*
                        echo "<td>";
                        echo "<form action='php/update_progress_notes.php' method='POST'>";
                        echo "<input type='hidden' name='ticket_id' value='{$row['id']}'>";
                        echo "<textarea name='progress_notes' rows='2' cols='30'>{$row['progress_notes']}</textarea>"; // Display progress notes as textarea
                        echo "<br>";
                        echo "<input type='submit' value='Save'>";
                        echo "</form>";
                        echo "</td>";
                        */
                        echo "<td><span class=\"w3-light-grey w3-round-xlarge w3-border w3-border-grey\" style='padding: 3px 10px;'>{$row['priority']}</span></td>";
                        echo "<td>{$row['assignee']}</td>";
                        echo "<td>{$row['date_created']}</td>";
                        echo "</tr>";
                    }

                }

                $conn->close();
                ?>

            </table>


        </div>
    </div>
</div>

<script>
    function openPopup(url, name) {
        var windowFeatures = "width=400,height=700,top=100,left=200,resizable=no,scrollbars=no,toolbar=no,menubar=no,location=no,status=no";

        window.open(url, name, windowFeatures);
    }

    function openURL(url) {
        window.location.href = url;
    }

    function dropdown(id) {
        var x = document.getElementById(id);
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }

    function promptFunction(id, subject) {
        var msg = "Ticket ID: " + id + "\nSubject: " + subject;
        alert(msg);
    }

</script>








</body>
</html>