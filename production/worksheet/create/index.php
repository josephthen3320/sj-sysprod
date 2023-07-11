<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
    $conn = getConnUser();

    session_start();

    $msg = $success_msg = '';
    if (isset($_SESSION['msg'])) {
        $msg = $_SESSION['msg'];
        unset($_SESSION['msg']);
    }

    if (isset($_SESSION['success_msg'])) {
        $success_msg = $_SESSION['success_msg'];
        unset($_SESSION['success_msg']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Worksheet</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>
<script src="/assets/js/utils.js"></script>

<body>
<?php $conn = getConnProduction(); ?>

<?php include
    $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_worksheet.php";
    $worksheet_id = generateWorksheetId();
?>

<div class="w3-container classification-content" id="worksheet-modal" style="">
    <div class="w3-container">
        <h3>Create Worksheet</h3>
        <span class="w3-text-red"><b><?= $msg ?></b></span>
        <span class="w3-text-green"><b><?= $success_msg ?></b></span>
    </div>

    <!-- TODO: REMOVE THIS ON PRODUCTION -->

    <form action="insert-worksheet.php" method="POST">
        <input required hidden readonly type="text" name="worksheet_id" id="worksheet_id" value="<?php echo $worksheet_id?>">

        <!-- Form Head -->
        <div class="w3-container w3-padding-32">
            <div class="w3-container w3-col l6 m6 s12">
                <span style="display: inline-block; width: 120px;"><b>Worksheet ID: </b></span><?= $worksheet_id ?><br>
                <span style="display: inline-block; width: 120px;"><b>Worksheet Date: </b></span><?= date("d M Y") ?>
                <br><br>
                <!-- FOB Button -->
                <input class="w3-check" type="checkbox" name="is_fob" id="is_fob">&nbsp;&nbsp;&nbsp;&nbsp;<label for="is_fob">FOB?</label>
            </div>

            <!-- Article image container -->
            <div class="w3-container w3-col l6 m6 s12 w3-center">
                <img class="w3-card" src="/img/img-placeholder.jpg" height="120px" name="sampleImg" id="imageField" />
            </div>
        </div>

        <!-- Form Container -->
        <div class="w3-container w3-col l12 m12 s12">
            <!-- Left side form -->
            <div class="w3-col l6 m12 s12">
                <!-- Est. Delivery Date -->
                <div class="w3-container w3-col l6 m6 s12">
                    <label>Delivery Date</label>
                    <div class="w3-cell-row">
                        <div class="w3-third w3-col">
                            <select required class="w3-select w3-border-right" name="delivery_day_date">
                                <option value="" selected hidden disabled>Select</option>
                                <option value="1">Early</option>
                                <option value="10">Mid</option>
                                <option value="20">Late</option>
                            </select>
                        </div>
                        <input required class="w3-input w3-col w3-twothird" type="month" id="monthYearInput" name="delivery_date">
                    </div>
                </div>

                <!-- PO Date -->
                <div class="w3-container w3-col l6 m6 s12">
                    <label>PO Date:</label>
                    <input class="w3-input" type="date" value="" id="po_date" name="po_date">
                </div>

                <!-- Est Qty -->
                <div class="w3-container w3-col l6 m6 s12">
                    <label>Estimated Qty:</label>
                    <input required id="qty" name="qty" class="w3-input" type="number" placeholder="Qty">
                </div>

                <!-- Customer Dropdown -->
                <div class="w3-container w3-col l6 m6 s12">
                    <label>Customer:</label>
                    <select required id="customer_id" name="customer_id" class="w3-select">
                        <option selected disabled hidden value="">Please select</option>
                        <?php
                        include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/utilities/util_classification.php";
                        $customers = fetchCustomers();
                        foreach ($customers as $customer) {
                            $id = $customer['customer_id'];
                            $name = $customer['customer_name'];

                            echo "<option value='{$id}'>{$name}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Description TextArea -->
                <div class="w3-container w3-col l12 m12 s12">
                    <label>Description:</label>
                    <textarea required name="description" id="description" class="w3-input" rows="4">-</textarea>
                </div>

                <!-- Cloth Width -->
                <div class="w3-container w3-col l6 m6 s12">
                    <label>Cloth width:</label>
                    <input name="cloth_width" id="cloth_width" class="w3-input" type="number" placeholder="Cloth Width">
                </div>
            </div>

            <!-- Right side form -->
            <div class="w3-col l6 m12 s12">
                <script src="/assets/js/search-article.js"></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <!-- Article Selection -->
                <div class="w3-container">
                    <label>Article No.</label>
                    <span class="w3-right" style="cursor: pointer;" onclick="openSearchPopup()"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input id="articleInput" name="article_id" required readonly class="w3-input" placeholder="Click to search" onclick="openSearchPopup()">
                </div>

                <div class="w3-container w3-col l6 m6 s12">
                    <label>Model Name</label>
                    <input name="art_name" id="modelNameField" class="w3-input" readonly>
                </div>

                <div class="w3-container w3-col l6 m6 s12">
                    <label>Brand</label>
                    <input name="art_brand" id="brandIdField" class="w3-input" readonly>
                </div>

                <div class="w3-container w3-col l6 m6 s12">
                    <label>Embro CMT</label>
                    <input name="art_cmt_embro" id="embroCmtIdField" class="w3-input" readonly>
                </div>

                <div class="w3-container w3-col l6 m6 s12">
                    <label>Print CMT</label>
                    <input name="art_cmt_print" id="printCmtIdField" class="w3-input" readonly>
                </div>

                <div class="w3-container w3-col l6 m6 s12">
                    <label>Is Rib</label>
                    <input name="art_rib" id="isRibField" class="w3-input" readonly>
                </div>

                <div class="w3-container w3-col l6 m6 s12">
                    <label>Sample Code</label>
                    <input name="art_sample_code" id="sampleCodeField" class="w3-input" readonly>
                </div>
            </div>

            <div class="w3-container w3-col l12 m12 s12 w3-padding-64">
                <button class="w3-button w3-blue-grey w3-bar w3-padding" type="submit">Create worksheet &nbsp;&nbsp; <i class="fa-solid fa-right"></i></button>
            </div>


            <?php /** TODO: LOG WORKSHEET CREATION */ ?>
    </form>
</div>

</body>
</html>