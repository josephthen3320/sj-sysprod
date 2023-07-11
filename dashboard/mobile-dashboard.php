<?php
date_default_timezone_set("Asia/Jakarta");

// Get the current time
$currentHour = date('H');

// Initialize the greeting variable
$greeting = '';

// Determine the time of day and set the greeting accordingly
if ($currentHour >= 5 && $currentHour < 12) {
    $greeting = 'morning';
} elseif ($currentHour >= 12 && $currentHour < 18) {
    $greeting = 'afternoon';
} elseif ($currentHour >= 18 && $currentHour < 22) {
    $greeting = 'evening';
} else {
    $greeting = 'night';
}
?>

<div class="" style="padding-top: 50px; background-color: #fbfbfb; height: 100vh;">
    <div class="w3-bar w3-container w3-margin-bottom">
        <span class="" style="">Good <?= $greeting ?>, <b><?= $name ?></b></span>
    </div>

    <div class="w3-container">
        <div class="w3-row w3-card w3-round-large w3-white" style="min-height: 64px;">
            <div class="w3-border-bottom w3-round-large" style="height: 56px;">
                <div class="w3-col s2" style="background-color: #0B293C; height: inherit; display: flex; justify-content: center; align-items: center;">
                    <i class="fas fa-fw fa-calendar-days fa-xl w3-text-white"></i>
                </div>
                <div class="w3-col s10 w3-container" style="height: inherit; display: flex; align-items: center;">
                    <h4><?= date('D, d M Y'); ?></h4>
                </div>
            </div>


            <div class="" style="min-height: 64px;">
                <div class="w3-col s2" style="display: flex; align-items: center; justify-content: center; padding-top: 16px">
                    <i class="fas fa-megaphone fa-fw"></i>
                </div>
                <div class="w3-col s10" style="display: flex; align-items: center; padding-top: 10px; padding-bottom: 10px;">
                    <?php
                    $connLog = getConnLog();
                    $sql = "SELECT * FROM global_announcements ORDER BY timestamp DESC LIMIT 1";
                    $result = $connLog->query($sql);
                    $announcement = $result->fetch_assoc();
                    $connLog->close();
                    ?>
                    <span><b><?= $announcement['subject'] ?></b><br><?= $announcement['details'] ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="w3-row w3-container">
        <div class="w3-col l12 m12 s12 w3-border-top" style="padding-bottom: 16px; margin-top: 16px; padding-top: 16px">
            <span><b>QUICK LINKS</b></span>
        </div>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('https://hr.talenta.co/')">
            <img src="/assets/logo/logo_talenta.webp" height="30"><br>
            <span class="w3-small">Talenta</span>
        </button>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('https://account.accurate.id/)">
            <img src="/assets/logo/logo_accurate.png" height="30"><br>
            <span class="w3-small">Accurate</span>
        </button>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('https://accounts.ginee.com/)">
            <img class="w3-round-large" src="/assets/logo/logo_ginee.png" height="30"><br>
            <span class="w3-small">Ginee</span>
        </button>
    </div>

    <?php if ($_SESSION['user_role'] <= 1) {
      include_once "admin-buttons.php";
    }
    ?>

    <div class="w3-row w3-container w3-padding">
    <!-- Production Buttons -->
        <div class="w3-col l12 m12 s12 w3-border-top" style="padding-bottom: 16px; padding-top: 16px;">
            <span><b>PRODUCTION</b></span>
        </div>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('/production/classification.php')">
            <i class="fas fa-fw fa-box-archive fa-xl w3-text-orange"></i><br>
            <span class="w3-small">Classification</span>
        </button>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('/production/article.php')">
            <i class="fas fa-fw fa-shirt fa-xl w3-text-indigo"></i><br>
            <span class="w3-small">Article</span>
        </button>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('/production/worksheet.php')">
            <i class="fas fa-fw fa-file-lines fa-xl w3-text-green"></i><br>
            <span class="w3-small">Worksheet</span>
        </button>
    </div>

    <div class="w3-row w3-container w3-padding">
    <!-- Transaction Buttons -->
        <div class="w3-col l12 m12 s12 w3-border-top" style="padding-bottom: 16px; padding-top: 16px;">
            <span><b>TRANSACTION</b></span>
        </div>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('/transaction/pola-marker')">
            <i class="fas fa-fw fa-draw-square fa-xl w3-text-green"></i><br>
            <span class="w3-small">Pola Marker</span>
        </button>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('/transaction/cutting')">
            <i class="fas fa-fw fa-scissors fa-xl w3-text-red"></i><br>
            <span class="w3-small">Cutting</span>
        </button>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('/transaction/embro')">
            <i class="fas fa-fw fa-scarf fa-xl w3-text-deep-purple"></i><br>
            <span class="w3-small">Embro</span>
        </button>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('/transaction/print-sablon')">
            <i class="fas fa-fw fa-pen-paintbrush fa-xl w3-text-blue"></i><br>
            <span class="w3-small">Print/Sablon</span>
        </button>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('/transaction/qc-embro')">
            <i class="fas fa-fw fa-clipboard-check fa-xl w3-text-teal"></i><br>
            <span class="w3-small">QC Embro</span>
        </button>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('/transaction/sewing')">
            <i class="fas fa-fw fa-reel fa-xl w3-text-pink"></i><br>
            <span class="w3-small">Sewing (CMT)</span>
        </button>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('/transaction/finishing')">
            <i class="fas fa-fw fa-list fa-xl w3-text-blue-grey"></i><br>
            <span class="w3-small">Finishing</span>
        </button>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('/transaction/washing')">
            <i class="fas fa-fw fa-washer fa-xl w3-text-indigo"></i><br>
            <span class="w3-small">Washing</span>
        </button>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('/transaction/qc-final')">
            <i class="fas fa-fw fa-clipboard-check fa-xl w3-text-teal"></i><br>
            <span class="w3-small">QC Final</span>
        </button>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('/transaction/perbaikan')">
            <i class="fas fa-fw fa-screwdriver-wrench fa-xl w3-text-brown"></i><br>
            <span class="w3-small">Perbaikan</span>
        </button>
        <button style='height: 70px;' class="w3-button w3-col s4 w3-padding-16" onclick="openURL('/transaction/warehouse')">
            <i class="fas fa-fw fa-warehouse fa-xl w3-text-blue-grey"></i><br>
            <span class="w3-small">Gudang</span>
        </button>

    </div>
</div>
