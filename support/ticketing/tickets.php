<!-- tickets.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ITS | View Tickets</title>
    <!-- CSS styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f7f7f7;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        a {
            color: #000;
            text-decoration: none;
        }

        a:hover {
            color: #333;
        }

        .no-tickets {
            text-align: center;
            font-style: italic;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>ITS Ticketing System</h1>
    <?php
    // Include the database connection file
    require_once 'php/db-its.php';

    // Fetch all tickets from the database
    $sql = "SELECT * FROM tickets";
    $result = mysqli_query($conn, $sql);

    // Check if any tickets are available
    if (mysqli_num_rows($result) > 0) {
        // Display the tickets in a table
        echo "<h2>Available Tickets</h2>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Title</th><th>Description</th><th>Status</th><th>Progress Notes</th><th>Submitted By</th><th>Department</th><th>Created At</th></tr>";

        // Loop through each ticket and display its details
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['title']}</td>";
            echo "<td>{$row['description']}</td>";
            echo "<td>";
            echo "<form action='php/update_status.php' method='POST'>";
            echo "<input type='hidden' name='ticket_id' value='{$row['id']}'>";
            echo "<select name='status' onchange='this.form.submit()'>";
            echo "<option value='open' " . ($row['status'] == 'open' ? 'selected' : '') . ">Open</option>";
            echo "<option value='closed' " . ($row['status'] == 'closed' ? 'selected' : '') . ">Closed</option>";
            echo "<option value='in-progress' " . ($row['status'] == 'in-progress' ? 'selected' : '') . ">In Progress</option>";
            echo "<option value='pending' " . ($row['status'] == 'pending' ? 'selected' : '') . ">Pending</option>";
            echo "</select>";
            echo "</form>";
            echo "</td>";
            echo "<td>";
            echo "<form action='php/update_progress_notes.php' method='POST'>";
            echo "<input type='hidden' name='ticket_id' value='{$row['id']}'>";
            echo "<textarea name='progress_notes' rows='2' cols='30'>{$row['progress_notes']}</textarea>"; // Display progress notes as textarea
            echo "<br>";
            echo "<input type='submit' value='Save'>";
            echo "</form>";
            echo "</td>";
            echo "<td>{$row['submitted_by']}</td>";
            echo "<td>{$row['department']}</td>";
            echo "<td>{$row['created_at']}</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<h2>No Tickets Available</h2>";
    }

    // Close the database connection
    mysqli_close($conn);
    ?>
</body>
</html>
