<?php
    function goBack() {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (isset($_POST['back_button'])) {
        goBack();
    }
?>

<script src="/assets/js/utils.js"></script>

<script>
    function sidenavOpen() {
        document.getElementById('sidenavbar').style.width = "100%";
        document.getElementById('sidenavbar').style.display = "block";
    }

    function sidenavClose() {
        document.getElementById('sidenavbar').style.display = "none";
    }
</script>

<!-- Mobile -->
<div class="w3-bar w3-top w3-text-white w3-hide-large" style="background-color: #0B293C; height: 64px;">
    <div class="w3-row" style="height: inherit;">
        <button class="w3-col s2 w3-button jt-orange" onclick="sidenavOpen()" style="height: inherit;"><i class="fas fa-bars"></i></button>

        <div class="w3-col s8 w3-display-container" style="height: inherit;">
            <span class="w3-display-middle"><b><?= $page_title ?></b></span>
        </div>

        <button class="w3-col s2 w3-button w3-red" onclick="openURL('/php-modules/logout.php')" style="height: inherit;"><i class="fas fa-right-from-bracket"></i></button>
    </div>

    <button class="w3-button w3-bar-item jt-orange" onclick="sidenavOpen()" name="btnMenu" id="btnMenu" style="height: inherit; align-items: center;">
        <i class="fa-solid fa-bars"></i>
    </button>
</div>

<div class="w3-sidebar w3-bar-block w3-hide-large" style="display:none; width: 100%; top: 0; padding-bottom: 64px;" id="sidenavbar">
    <div class="w3-bar w3-top w3-text-white" style="background-color: #0B293C; height: 64px;">
        <div class="w3-row" style="height: inherit;">
            <button class="w3-col s2 w3-button w3-red" onclick="sidenavClose()" style="height: inherit;"><i class="fas fa-x"></i></button>

            <div class="w3-col s8 w3-display-container" style="height: inherit;">
                <span class="w3-display-middle"><b>Navigation</b></span>
            </div>

            <div class="w3-col s2" style="height: inherit;"></div>
        </div>
    </div>
    <div class="w3-animate-left" style="margin-top: 64px;">

        <span class="w3-bar-item w3-center w3-light-grey" style="font-weight: bolder;">GENERAL</span>
        <a href="/" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">Dashboard</a>
        <a href="/dashboard" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">Support</a>

        <?php
        if ($_SESSION['user_role'] <= 1) {
            echo "<a href='/admin/user-management' class='w3-bar-item w3-button w3-padding-16' style='padding-left: 64px;'>User Management</a>";
            echo "<a href='/admin/announcement' class='w3-bar-item w3-button w3-padding-16' style='padding-left: 64px;'>Announcements</a>";
        }

        ?>



        <span class="w3-bar-item w3-center w3-light-grey" style="font-weight: bolder;">PRE-PRODUCTION</span>
        <a href="/production/classification.php" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">Classification</a>
        <a href="/production/article.php" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">Article</a>
        <a href="/production/worksheet.php" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">Worksheet</a>

        <span class="w3-bar-item w3-center w3-light-grey" style="font-weight: bolder;">TRANSACTION</span>
        <a href="/transaction/pola-marker" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">Pola Marker</a>
        <a href="/transaction/cutting" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">Cutting</a>
        <a href="/transaction/embro" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">Embro</a>
        <a href="/transaction/print-sablon" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">Print/Sablon</a>
        <a href="/transaction/qc-embro" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">QC Embro</a>
        <a href="/transaction/sewing" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">Sewing (CMT)</a>
        <a href="/transaction/finishing" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">Finishing</a>
        <a href="/transaction/washing" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">Washing</a>
        <a href="/transaction/qc-final" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">QC Final</a>
        <a href="/transaction/perbaikan" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">Perbaikan</a>
        <a href="/transaction/warehouse" class="w3-bar-item w3-button w3-padding-16" style="padding-left: 64px;">Gudang</a>
    </div>

</div>



<!-- Widescreen -->
<div class="w3-bar w3-text-white w3-hide-small w3-hide-medium" style="display: flex; background-color: #0B293C; width: 75%; height: 64px; align-items: center; position: fixed; top:0; z-index: 9999; /* Set a high z-index value */">
    <button class="w3-button w3-bar-item jt-orange" onclick="goBack()" name="back_button" style="height: inherit; align-items: center;">
        <i class="fa-solid fa-arrow-left"></i>
    </button>

    <span class="w3-bar-item"><b><?php echo $page_title ?></b></span>

    <!-- User button top right -->
    <div class="w3-button w3-bar-item jt-orange" onclick="dropdown('userdropmodal')" style="display:flex; width: 250px; height: inherit; align-items: center; right: 0; position:absolute">
        <div class="w3-quarter" style="display: flex; align-items: center; justify-content: center;">
            <div class="w3-container w3-circle w3-white" style="height: 40px; width: 42px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-user fa-2xl" style="color: #0B293C"></i>
            </div>
        </div>
        <div class="w3-half">
            <?php echo "<b>{$name}</b>"; ?>
        </div>
        <div class="w3-quarter">
            <i class="fa-solid fa-chevron-down"></i>
        </div>
    </div>
    <div id="userdropmodal" class="w3-dropdown-content w3-bar-block w3-border-2 w3-card" style="position: fixed; width:250px; right: 0; margin-top:135px;">
        <a href="#" class="w3-bar-item w3-button">My Profile</a>
        <a href="/php-modules/logout.php" class="w3-bar-item w3-button">Logout &nbsp;&nbsp; <i class="fa-solid fa-right-from-bracket"></i></a>
    </div>

</div>