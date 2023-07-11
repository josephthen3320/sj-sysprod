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

<div class="w3-container classification-content" id="cmt_modal" style="">
    <h3>CMTs</h3>
    <?php $cmts = include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/fetch/master-data/fetch-cmt.php"; ?>
    <!-- Todo: create & edit & view -->

    <!-- Buttons Container -->
    <div class="w3-bar" style="margin-bottom: 12px;">
        <button class="w3-bar-item w3-button w3-green" onclick="openPopup('action/add-cmt.php', 'class-add')">
            <i class="fa-solid fa-plus-circle"></i> Add CMT
        </button>
    </div>

    <table class="w3-table w3-table-all">
        <thead>
        <tr>
            <th>No</th>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Location</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $i = 0;
        $cmt_type_name = "";
        foreach ($cmts as $cmt) :
            $cmt_id = $cmt['id'];
            $edit_url = "action/edit-cmt.php?id={$cmt_id}";
            $delete_url = "action/delete-cmt.php?id={$cmt_id}";

            switch ($cmt['cmt_type']) {
                case 'CT1':     // Cutting
                    $cmt_type_name = "Cutting";
                    break;
                case 'CT2':     // Embro Sablon
                    $cmt_type_name = "Embro/Sablon";
                    break;
                case 'CT3':     // Finishing
                    $cmt_type_name = "Finishing";
                    break;
                case 'CT6':     // Washing
                    $cmt_type_name = "Washing";
                    break;
                case 'CT4':     // Gudang
                    $cmt_type_name = "Gudang";
                    break;
                case 'CT5':     // Jahit
                    $cmt_type_name = "Jahit";
                    break;
            }

            echo "<tr>";
            echo "<td>" . ++$i . "</td>";
            echo "<td>{$cmt['cmt_id']}</td>";
            echo "<td>{$cmt['cmt_name']}</td>";
            echo "<td>{$cmt_type_name}</td>";
            echo "<td>{$cmt['location']}</td>";
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