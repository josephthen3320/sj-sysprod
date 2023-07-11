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

<body>

<div class="w3-container classification-content" id="customer_modal" style="">
    <h3>Customers</h3>
    <?php $customers = include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/fetch/master-data/fetch-customer.php"; ?>
    <!-- Todo: create & edit & view -->

    <!-- Buttons Container -->
    <div class="w3-bar" style="margin-bottom: 12px;">
        <button class="w3-bar-item w3-button w3-green" onclick="openPopup('action/add-customer.php', 'class-add')">
            <i class="fa-solid fa-plus-circle"></i> Add Customer
        </button>
    </div>

    <table class="w3-table w3-table-all">
        <thead>
        <tr>
            <th>No</th>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $i = 0;
        foreach ($customers as $customer) :
            $customer_id = $customer['id'];
            $edit_url = "action/edit-customer.php?id={$customer_id}";
            $delete_url = "action/delete-customer.php?id={$customer_id}";

            echo "<tr>";
            echo "<td>" . ++$i . "</td>";
            echo "<td>{$customer['customer_id']}</td>";
            echo "<td>{$customer['customer_name']}</td>";
            echo "<td>
                    <button class='w3-button w3-round w3-blue' onclick='openPopup(\"" . $edit_url . "\", \"action\")'>
                        <i class=\"fa-solid fa-pen\"></i>&nbsp;&nbsp;Edit
                    </button>
                    <button class='w3-button w3-round w3-red' onclick='openPopup(\"" . $delete_url . "\", \"action\")'>
                        <i class=\"fa-solid fa-trash\"></i>
                    </button>
                  </td>";
            echo "<tr>";
        endforeach;
        ?>

        </tbody>
    </table>
</div>

</body>
</html>