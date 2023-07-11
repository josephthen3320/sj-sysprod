<!-- ticketing/index.php -->
<?php
session_start();

$page_title = "Ticketing";

// TODO: Change this to actual user role
$user_role = "Kucing Admin";
$top_title = "Ticketing System";

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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITS Ticketing</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">

    <style>
        body {
            background-color: #F4EDE9;
        }
        .w3-top {
            margin-bottom: 60px; /* Add margin-bottom to push the top bar down */
        }

        .w3-sidebar {
            top: 50px; /* Add top positioning to the left bar */
        }

        .jt-orange {
            background-color: #ff5722;
        }
    </style>
</head>
<body>



<!-- Left bar -->
<?= include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/sidebar.php" ; ?>

<div class="w3-threequarter w3-white" style="min-height: 100vh; margin-left: 25%;">
    <?= include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div style="margin-top: 16px;">
        <div class="w3-bar w3-white w3-border-bottom " style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>New Ticket</b></span>
        </div>

        <!-- CONTENT HERE -->
        <div class="w3-container w3-padding-16" style="min-height: 100vh; background-color: #fbfbfb">

            <div class="w3-padding">

                <form action="php/create_ticket.php" method="POST">
                    <div class="w3-cell-row">
                        <div class="w3-container w3-cell w3-third">
                            <label for="submitted_by">Contact:</label>
                            <input class="w3-input w3-round w3-border w3-border-black" value="<?= $name ?>" type="text" id="requester" name="requester" readonly required placeholder="Name">
                        </div>
                        <div class="w3-container w3-cell w3-third">
                            <label for="submitted_by">Department:</label>
                            <input class="w3-input w3-round w3-border w3-border-black" value="<?= "ITS" ?>" type="text" id="department" name="department" readonly required placeholder="Name">
                        </div>
                        <div class="w3-container w3-cell w3-third">
                            <label for="submitted_by">Date:</label>
                            <input class="w3-input w3-round w3-border w3-border-black" value="<?= date("d M Y") ?>" type="text" readonly>
                        </div>
                    </div>

                    <div class="w3-cell-row w3-padding-top-32 w3-padding-16">
                        <div class="w3-container w3-cell">
                            <label for="title">Subject:</label>
                            <input class="w3-input w3-round w3-border w3-border-black" type="text" id="subject" name="subject" required placeholder="What is your issue?">
                        </div>
                    </div>
                    <div class="w3-cell-row w3-padding-16">
                        <div class="w3-container w3-cell w3-quarter">
                            <label for="type">Type:</label>
                            <select class="w3-select w3-round w3-border w3-border-black" id="type" name="type" required>
                                <option value=""  selected>Please select</option>
                                <option value="service">Service Request</option>
                                <option value="incident">Incident</option>
                                <option value="problem">Problem</option>
                                <option value="change">Change Request</option>
                            </select>
                        </div>
                        <div class="w3-container w3-cell w3-quarter">
                            <label for="area">Relating to:</label>
                            <select class="w3-select w3-round w3-border w3-border-black" id="module" name="module" required>
                                <option value=""  selected>Please select</option>
                                <option value="service">Production System</option>
                                <option value="incident">Talenta</option>
                                <option value="problem">Ginee</option>
                                <option value="change">General</option>
                            </select>
                        </div>
                        <div class="w3-container w3-cell w3-quarter">
                            <label for="status">Status:</label>
                            <select class="w3-select w3-round w3-border w3-border-black w3-" id="status" name="status" required >
                                <option value="open" selected>Open</option>
                                <option value="inprogress">In-Progress</option>
                                <option value="closed">Closed</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="w3-container w3-cell w3-quarter">
                            <label for="priority">Priority:</label>
                            <select class="w3-select w3-round w3-border w3-border-black w3-" id="priority" name="priority" required >
                                <option value="low" selected>Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                    </div>

                    <div class="w3-cell-row w3padding-16">
                        <div class="w3-container w3-cell">
                            <label for="description">Assigned to:</label>
                            <select class="w3-select w3-round w3-border w3-border-dark-grey w3-" required id="assignee" name="assignee">
                                <option selected value="its">IT Services</option>
                            </select>

                        </div>
                    </div>

                    <div class="w3-cell-row w3-padding-16">
                        <div class="w3-container w3-cell">
                            <label for="description">Description:</label>
                            <textarea rows="5" class="w3-input w3-round w3-border w3-border-black" id="description" name="description" required></textarea>
                        </div>
                    </div>

                    <div class="w3-cell-row w3-padding-16">
                        <div class="w3-quarter w3-container w3-cell">
                            <label for="tags">Tags:</label>
                            <input class="w3-input w3-round w3-border w3-border-dark-gray" value="" id="labels" name="labels">
                        </div>
                    </div>

                    <div class="w3-cell-row w3-padding-64 w3-container">
                        <button class="w3-button w3-bar w3-padding-16 w3-round-xlarge w3-blue" type="submit">Submit</button>
                    </div>
                </form>

            </div>


        </div>

        <script>
            function openPopup(url, name) {
                var windowFeatures = "width=400,height=700,top=100,left=200,resizable=no,scrollbars=no,toolbar=no,menubar=no,location=no,status=no";

                window.open(url, name, windowFeatures);
            }

            function dropdown(id) {
                var x = document.getElementById(id);
                if (x.className.indexOf("w3-show") == -1) {
                    x.className += " w3-show";
                } else {
                    x.className = x.className.replace(" w3-show", "");
                }
            }
        </script>

</body>
</html>

<!--DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ITS Ticketing System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f7f7f7;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>ITS Ticketing System</h1>
    <form action="create_ticket.php" method="POST">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="submitted_by">Submitted By:</label>
        <input type="text" id="submitted_by" name="submitted_by" required>

        <label for="department">Department:</label>
        <input type="text" id="department" name="department" required>

        <button type="submit">Submit</button>
    </form>
</body>
</html-->
