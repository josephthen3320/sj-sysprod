<?php
        include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_worksheet.php';
        include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_articles.php';
        include_once $_SERVER['DOCUMENT_ROOT'] . '/php-modules/utilities/util_classification.php';

        $worksheet_id   = getWorksheetIdByGlobalId($_GET['id']);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Viewing: <?php print $worksheet_id; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<style>
    .editable {
        background-color: #e7f8fa;
        cursor: pointer;
    }

    @media print{
        .print-hide {
            display: none;
        }
        .print-show {
            display: block;
        }
    }
</style>


<style type="text/css">
    .tg  {border-collapse:collapse;border-spacing:0;}
    .tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
        overflow:hidden;padding:2px 1px;word-break:normal;}
    .tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
        font-weight:normal;overflow:hidden;padding:1px 1px;word-break:normal;}
    .tg .tg-abx8{background-color:#c0c0c0;font-weight:bold;text-align:left;vertical-align:top}
    .tg .tg-ncfi{background-color:#efefef;font-weight:bold;text-align:center;vertical-align:middle}
    .tg .tg-y6fn{background-color:#c0c0c0;text-align:left;vertical-align:top}
    .tg .tg-0lax{text-align:left;vertical-align:top}
</style>

<body>

    <?php

        $worksheet      = fetchWorksheet($worksheet_id)->fetch_assoc();
        $article        = getArticleById($worksheet['article_id']);


        /* Worksheet Information */
        $worksheet_date     = $worksheet['worksheet_date'];
        $delivery_date      = $worksheet['delivery_date'];

        $categoryName       = getCategoryNameById($article['category_id']);
        $subcategoryName    = getSubcategoryNameById($article['subcategory_id']);

        /* Article Information */
        $article_id         = $worksheet['article_id'];
        $art_cmt_embro      = getCMTNameById($article['embro_cmt_id']);
        $art_cmt_print      = getCMTNameById($article['print_cmt_id']);
        $art_name           = $article['model_name'];
        $qty                = $worksheet['qty']; // Example value
        $washes             = implode(", ", fetchWashNamesByArticleId($article_id)); // Example value
        $cloth_width        = $worksheet['cloth_width']; // Example value
        $art_brand          = getBrandNameById($article['brand_id']); // Example value
        $art_rib            = $article['is_rib']; // Example value

        $art_sample_code    = $article['sample_code']; // Example value
        $image              = "/img/articles/" . $article['sample_img_path']; // Example value
        $description        = $worksheet['description']; // Example value
        $sideDesc           = $worksheet['desc_side']; // Example value
        $belowDesc          = $worksheet['desc_below']; // Example value

        $fabric_utama       = $worksheet['fabric_utama']; // Example value
        $order              = $worksheet['w_order']; // Example value
        $repeat             = $worksheet['w_repeat']; // Example value
        $generalEstCons     = $worksheet['general_est_cons']; // Example value


    ?>

    <div class="header">
        <div class="w3-white w3-center w3-bar w3-padding print-hide">
            <span class="w3-monospace"><b>*** WORKSHEET ***</b></span><br>
            <button class="w3-button w3-green" onclick="window.print()">Print &nbsp; <i class="fas fa-print"></i></button>
        </div>
        <div class="w3-bar" style="display: flex; justify-content: center; background-color: #10809e">
            <span class="w3-bar-item w3-text-white" style="font-weight: bold;"><?= $categoryName ?></span>
        </div>
        <div class="w3-bar" style="display: flex; justify-content: center; background-color: #1495bb">
            <span class="w3-bar-item w3-text-white w3-small" style="font-weight: bold;"><?= $subcategoryName ?></span>
        </div>
    </div>

    <!-- Bar ID -->
    <div class="w3-bar w3-border w3-tiny" style="background-color: #fff">
        <span class="w3-bar-item"><?= "TGL WKS:" ?></span>
        <span class="w3-bar-item"><?= $worksheet_date ?></span>

        <div class="w3-right">
            <span class="w3-bar-item"><?= "NO SPK:" ?></span>
            <span class="w3-bar-item"><?= $worksheet_id ?></span>
        </div>
    </div>

    <table class="w3-tiny w3-border-bottom w3-padding" style="width: 100%;">
        <tr>
            <td style="width: 10%">ARTIKEL:</td>
            <td><?php echo $article_id ?></td>
            <td style="width: 15%">ORDER:</td>
            <td id="w_order" data-worksheet-id="<?= $worksheet_id ?>"  class="editable" style="width: 10%;" contenteditable><?= $order ?></td>
            <td style="width: 5%;">EMBRO:</td>
            <td><?php echo $art_cmt_embro ?></td>
            <td style="width: 15%">CMT:</td>
            <td style="width: 5%">--</td>
        </tr>
        <tr>
            <td style="width: 10%">MODEL:</td>
            <td><?php echo $art_name ?></td>
            <td style="width: 15%">FABRIC UTAMA:</td>
            <td id="fabric_utama" data-worksheet-id="<?= $worksheet_id ?>"  class="editable" style="width: 10%;" contenteditable><?= $fabric_utama ?></td>
            <td style="width: 5%;">PRINT:</td>
            <td><?php echo $art_cmt_print ?></td>
            <td style="width: 15%">GENERAL EST. CONS:</td>
            <td id="general_est_cons" data-worksheet-id="<?= $worksheet_id ?>" class="editable" style="width: 5%;" contenteditable><?= $generalEstCons ?></td>
        </tr>
        <tr>
            <td style="width: 10%">Estimasi QTY:</td>
            <td><?php echo $qty ?></td>
            <td style="width: 15%">REPEAT:</td>
            <td id="w_repeat" data-worksheet-id="<?= $worksheet_id ?>"  class="editable" style="width: 10%;" contenteditable><?= $repeat ?></td>
            <td style="width: 5%;">WASH:</td>
            <td><?php echo $washes ?></td>
            <td style="width: 15%"></td>
            <td style="width: 5%"></td>
        </tr>
        <tr>
            <td style="width: 10%">DELIVERY:</td>
            <td><?= $delivery_date ?></td>
            <td style="width: 15%">LEBAR KAIN:</td>
            <td style="width: 10%;"><?php echo $cloth_width; ?></td>
            <td style="width: 5%;"></td>
            <td></td>
            <td style="width: 15%"></td>
            <td style="width: 5%"></td>
        </tr>
        <tr>
            <td style="width: 10%">MERK:</td>
            <td><?php echo $art_brand ?></td>
            <td style="width: 15%">RIB:</td>
            <td style="width: 10%;"><?php echo $art_rib; ?></td>
            <td style="width: 5%;"></td>
            <td></td>
            <td style="width: 15%"></td>
            <td style="width: 5%"></td>
        </tr>
    </table>

    <script>
        $(document).ready(function() {
            $('.editable').on('keyup', function() {
                var value = $(this).text().trim();
                var worksheetId = $(this).data('worksheet-id');
                var id = $(this).attr('id');

                $.ajax({
                    type: 'POST',
                    url: 'table/php/insert_' + id + '.php',
                    data: {
                        description: value,
                        worksheet_id: worksheetId
                    },
                    success: function(response) {
                        console.log('Response:', response);
                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', error);
                    }
                });
            });
        });
    </script>

    <div class="w3-tiny" style="display: flex;">
        <div class="" style="display: inline-block; width: 33.4%;">
            <div class="w3-bar w3-blue">
                <span class="w3-bar-item">SAMPLE MODEL: </span>
                <span class="w3-bar-item"><?php echo $art_sample_code ?></span>
            </div>
            <!-- image -->
            <div class="w3-container w3-center w3-padding">
                <img src="<?= $image ;?>" style="width: 80%;">
            </div>

            <div class="w3-bar w3-blue" style="display: flex;">
                <span class="w3-bar-item">PATTERN MODEL:</span>
            </div>

            <textarea id="descSide" style="display: inline-block;width: 100%; font-size: 32px; color: red; font-weight: bold; text-align: center;" rows="5" data-worksheet-id="<?= $worksheet_id ?>"><?= $sideDesc ?></textarea>
            <script>
                $(document).ready(function() {
                    $('#descSide').on('keyup', function() {
                        var value = $(this).val();

                        console.log(value);

                        var worksheetId = $(this).data('worksheet-id');

                        $.ajax({
                            type: 'POST',
                            url: 'table/php/insert_desc_side.php',
                            data: {
                                description: value,
                                worksheet_id: worksheetId
                            },
                            success: function(response) {
                                console.log('Response: ', response);
                            },
                            error: function(xhr, status, error) {
                                console.log('Error: ' + error);
                            }
                        });
                    });
                });
            </script>
        </div>

        <div class="w3-border-left" style="display: inline-block; width: 66.6%;">

            <?php include "table/01-qty-size.php"; ?>
            <?php include "table/02-size-spec.php"; ?>


            <div class="w3-bar" style="display: flex; justify-content: center;">
                <span class="w3-bar-item"><b>COMMENTS:</b></span>
            </div>
            <textarea id="description" style="display: inline-block; width: 100%; font-weight: bold; overflow: hidden;" rows="5" data-worksheet-id="<?= $worksheet_id ?>">
<?php echo $description; ?>
            </textarea>

            <script>
                $(document).ready(function() {
                    $('#description').on('keyup', function() {
                        var value = $(this).val();

                        console.log(value);

                        var worksheetId = $(this).data('worksheet-id');

                        $.ajax({
                            type: 'POST',
                            url: 'table/php/insert_description.php',
                            data: {
                                description: value,
                                worksheet_id: worksheetId
                            },
                            success: function(response) {
                                console.log('Response: ', response);
                            },
                            error: function(xhr, status, error) {
                                console.log('Error: ' + error);
                            }
                        });
                    });
                });
            </script>



            <textarea id="descBelow" style="display: inline-block; width: 100%; font-size: 24px; color: red; font-weight: bold; text-align: center;" rows="2" data-worksheet-id="<?= $worksheet_id ?>"><?= $belowDesc ?></textarea>

            <script>
                $(document).ready(function() {
                    $('#descBelow').on('keyup', function() {
                        var value = $(this).val();

                        console.log(value);

                        var worksheetId = $(this).data('worksheet-id');

                        $.ajax({
                            type: 'POST',
                            url: 'table/php/insert_desc_below.php',
                            data: {
                                description: value,
                                worksheet_id: worksheetId
                            },
                            success: function(response) {
                                console.log('Response: ', response);
                            },
                            error: function(xhr, status, error) {
                                console.log('Error: ' + error);
                            }
                        });
                    });
                });
            </script>

            <?php include "table/03-test-susut.php"; ?>

        </div>
    </div>

    <?php include "table/04-material-body.php"; ?>
    <?php include "table/05-material-accessories.php"; ?>

    <div class="w3-margin-top w3-row w3-margin-bottom">
        <div class="w3-col l2 m2 s2">&nbsp;</div>
        <div class="w3-col l2 m2 s2 w3-border w3-center">
            <div class="" style="height: 50px;"></div>
            <div class="w3-tiny">DIBUAT OLEH</div>
        </div>
        <div class="w3-col l2 m2 s2 w3-border w3-center">
            <div class="" style="height: 50px;"></div>
            <div class="w3-tiny">DIKETAHUI OLEH</div>
        </div>
        <div class="w3-col l2 m2 s2 w3-border w3-center">
            <div class="" style="height: 50px;"></div>
            <div class="w3-tiny">MARKETING</div>
        </div>
        <div class="w3-col l2 m2 s2 w3-border w3-center">
            <div class="" style="height: 50px;"></div>
            <div class="w3-tiny">PPIC</div>
        </div>
        <div class="w3-col l2 m2 s2 w3-border w3-center">
            <div class="" style="height: 50px;"></div>
            <div class="w3-tiny">QA</div>
        </div>
    </div>


    <div class="w3-tiny">
        <div class="w3-bar w3-border-top w3-border-bottom w3-padding w3-center">
            Generated on <?php date_default_timezone_set('Asia/Jakarta'); echo date("d-m-Y H:i:s", time());?>
        </div>
    </div>


</body>
</html>