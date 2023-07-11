<?php
session_start();

$root = $_SERVER['DOCUMENT_ROOT'];
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";


require_once $root . "/php-modules/db.php";
$conn = getConnProduction();

/* ----------------------------------- */

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the SQL query
    $query = "SELECT * FROM worksheet INNER JOIN worksheet_detail ON worksheet.worksheet_id = worksheet_detail.worksheet_id WHERE worksheet_detail.id = ?";
    //$query = "SELECT * FROM worksheet_detail WHERE id = ? INNER JOIN worksheet";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a row is found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $worksheet_id       = $row['worksheet_id'];
        $article_id         = $row['article_id'];
        $qty                = $row['qty'];
        $customer_id        = $row['customer_id'];
        $cloth_width        = $row['cloth_width'];
        $is_fob             = isset($row['is_fob']) ? "YES" : "NO";
        $is_pola            = isset($row['is_pola_use']) ? "YES" : "NO";
        $description        = $row['description'];

        $art_name           = $row['art_name'];
        $art_brand          = $row['art_brand'];
        $art_cmt_embro      = $row['art_cmt_embro'];
        $art_cmt_print      = $row['art_cmt_print'];
        $art_rib            = $row['art_rib'];
        $art_sample_code    = $row['art_sample_code'];

        $washes = implode("<br>", fetchWashNames($article_id));

        $sql = "SELECT sample_img_path FROM article WHERE article_id = '$article_id' ";
        $result = mysqli_query($conn, $sql);
        $img = mysqli_fetch_assoc($result)['sample_img_path'];

        $image = "/img/articles/" . $img;

        include $_SERVER['DOCUMENT_ROOT'] . '/php-modules/agents/logging.php';
        logGeneric($_SESSION['user_id'], 422, "MODIFY WORKSHEET; worksheetId={$worksheet_id}");

    } else {
        echo "<p>No data found for the specified ID.</p>";
    }
}


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
</head>

<style>
    .editable {
        background-color: #e7f8fa;
        cursor: pointer;
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

<div class="">
    <div class="w3-white w3-center w3-bar w3-padding">
        <span class="w3-monospace"><b>*** WORKSHEET ***</b></span>
    </div>
    <div class="w3-bar" style="display: flex; justify-content: center; background-color: #10809e">
        <span class="w3-bar-item w3-text-white w3-large"><?php echo "CATEGORY"; ?></span>
    </div>
    <div class="w3-bar" style="display: flex; justify-content: center; background-color: #1495bb">
        <span class="w3-bar-item w3-text-white"><?php echo "Subcategory"; ?></span>
    </div>

    <!-- Bar ID -->
    <div class="w3-bar w3-border w3-small" style="background-color: #fff">
        <span class="w3-bar-item"><?php echo "TGL WKS:"; ?></span>
        <span class="w3-bar-item"><?php echo "32-Dec-2999"; ?></span>

        <div class="w3-right">
            <span class="w3-bar-item"><?php echo "NO SPK:"; ?></span>
            <span class="w3-bar-item"><?php echo $worksheet_id; ?></span>
        </div>
    </div>

    <table class="w3-small w3-border-bottom w3-padding" style="width: 100%;">
        <tr>
            <td style="width: 10%">ARTIKEL:</td>
            <td><?php echo $article_id ?></td>
            <td style="width: 15%">ORDER:</td>
            <td class="editable" style="width: 10%;">xxxx</td>
            <td style="width: 5%;">EMBRO:</td>
            <td><?php echo $art_cmt_embro ?></td>
            <td style="width: 15%">CMT:</td>
            <td style="width: 5%">--</td>
        </tr>
        <tr>
            <td style="width: 10%">MODEL:</td>
            <td><?php echo $art_name ?></td>
            <td style="width: 15%">FABRIC UTAMA:</td>
            <td class="editable" style="width: 10%;">xxxx</td>
            <td style="width: 5%;">PRINT:</td>
            <td><?php echo $art_cmt_print ?></td>
            <td style="width: 15%">GENERAL EST. CONS:</td>
            <td class="editable" style="width: 5%;">xxx</td>
        </tr>
        <tr>
            <td style="width: 10%">Estimasi QTY:</td>
            <td><?php echo $qty ?></td>
            <td style="width: 15%">REPEAT:</td>
            <td class="editable" style="width: 10%;">--</td>
            <td style="width: 5%;">WASH:</td>
            <td><?php echo $washes ?></td>
            <td style="width: 15%"></td>
            <td style="width: 5%"></td>
        </tr>
        <tr>
            <td style="width: 10%">DELIVERY:</td>
            <td><?php echo "13-2999" ?></td>
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

    <div class="w3-small" style="display: flex;">
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

            <textarea style="display: inline-block;width: 100%; font-size: 32px; color: red; font-weight: bold; text-align: center;" rows="5"></textarea>
        </div>

        <div class="w3-border-left" style="display: inline-block; width: 66.6%;">

            <?php include "table/01-qty-size.php"; ?>
            <?php include "table/02-size-spec.php"; ?>


            <div class="w3-bar" style="display: flex; justify-content: center;">
                <span class="w3-bar-item"><b>COMMENTS:</b></span>
            </div>
            <textarea style="display: inline-block; width: 100%; font-weight: bold; overflow: hidden;" rows="10">
<?php echo $description; ?>
1. Model dan teknik jahitan ikuti sample
2. Cutting sesuai permintaan
3. Benang ATAS jahit Matching ukuran 20/2
4. Benang BAWAH jahit matching ukuran 40/2
5. SPI - 11
6. KANCING MATCHING 7 PCS/BAJU
7. BENANG PASANG DAN LUBANG KANCING MATCHING BODY
8. SAKU 1 DIBADAN DEPAN KIRI PAKAI TANPA TUTUP
9. JAHIT SAKU SESUAI SAMPLE
            </textarea>
            <textarea style="display: inline-block; width: 100%; font-size: 24px; color: red; font-weight: bold; text-align: center;" rows="2"></textarea>

            <?php include "table/03-test-susut.php"; ?>

        </div>
    </div>

    <?php include "table/04-material-body.php"; ?>
    <?php include "table/05-material-accessories.php"; ?>




    <?php
    // Close the statement and database connection
    $stmt->close();
    $conn->close();
    ?>


    <div class="w3-small">
        <div class="w3-bar w3-gray w3-padding w3-center">
            Generated on <?php date_default_timezone_set('Asia/Jakarta'); echo date("d-m-Y H:i:s", time());?>
        </div>
    </div>

</div>
</body>
</html>


<script>
    // Get all elements with the class "editable"
    var elements = document.getElementsByClassName("editable");

    // Loop through each element and add the "contenteditable" attribute
    for (var i = 0; i < elements.length; i++) {
        elements[i].setAttribute("contenteditable", true);
    }
</script>


<?php
function fetchWashNames($article_id) {
    include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db_prod.php";

    $sql = "SELECT wash_id FROM article_wash WHERE article_id = '$article_id'";
    $result = $conn->query($sql);

    $wash_names = array();

    // Check if the query was successful
    if ($result) {
        // Fetch all the rows
        $wash_ids = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $wash_ids[] = $row['wash_id'];
        }

        // Output the results
        foreach ($wash_ids as $w_id) {
            $sql = "SELECT wash_type_name FROM wash_type WHERE wash_type_id = '$w_id'";
            $result = $conn->query($sql);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $wash_names[] = $row['wash_type_name'];
                }
            }
        }
    }

    return $wash_names;

}
?>