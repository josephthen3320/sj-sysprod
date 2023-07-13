<style>
    /* Styles for action buttons */
    .action-button {
        font-weight: 400 !important;
        padding-left: 32px !important;
    }
    i {
        display: inline-block;
        width: 20px;
        justify-content: center;
    }

    .sj-content {
        margin-top: 45px;
    }


    @media only screen and (max-width: 600px) {
        .sj-content {
            margin-left: 0 !important;
            margin-bottom: 64px;
        }
    }
</style>

<!-- Side navigation bar -->
<div class="w3-quarter w3-text-white w3-hide-small" style="min-height: 100vh; position: fixed; background-color: #0B293C">
    <!-- Header -->
    <div class="w3-container w3-padding" style="display:flex; justify-content: center; align-items: center; width: 100%; height: 100px;">
            <img src="/assets/logo/sysprod_logo_w.png" height="80%">
    </div>

    <!-- General section -->
    <div class="w3-container w3-padding w3-border-bottom w3-border-top w3-border-orange" style="padding-left: 32px;">
        <span class="w3-large" style="font-weight: 400;">General</span>
    </div>

    <div class="w3-padding w3-bar-block" style="">
        <a class="w3-medium w3-button w3-bar-item action-button" href="/dashboard/"><i class="fa-solid fa-gauge-high w3-text-orange"></i> &nbsp;&nbsp; Dashboard</a>
        <a class="w3-medium w3-button w3-bar-item action-button" href="/support/"><i class="fa-solid fa-headset w3-text-red"></i> &nbsp;&nbsp; Support</a>
    </div>

    <div class="w3-container w3-padding w3-border-bottom w3-border-top w3-border-orange" style="padding-left: 32px;">
        <span class="w3-large" style="font-weight: 400;">Admin</span>
    </div>
    <div class="w3-padding w3-bar-block" style="">
    <?php
    $userManagementButton = '<a class="w3-medium w3-button w3-bar-item action-button" href="/admin/user-management"><i class="fa-solid fa-user w3-text-red"></i> &nbsp;&nbsp; User Management</a>';
    $activityLogButton = '<a class="w3-medium w3-button w3-bar-item action-button" href="/admin/activities"><i class="fa-solid fa-list-timeline w3-text-red"></i> &nbsp;&nbsp; Activity Log</a>';
    $announcementButton   = '<a class="w3-medium w3-button w3-bar-item action-button" href="/admin/announcement"><i class="fa-solid fa-megaphone w3-text-red"></i> &nbsp;&nbsp; Announcement</a>';

    echo ($_SESSION['user_role'] <= 1) ? $userManagementButton : "";
    echo ($_SESSION['user_role'] <= 1) ? $activityLogButton : "";
    echo ($_SESSION['user_role'] <= 1) ? $announcementButton : "";

    ?>
    </div>

    <!-- Production section -->
    <?php

        if ($_SESSION['user_role'] != 4) {
            echo "
            <div class=\"w3-container w3-padding w3-border-bottom w3-border-top w3-border-orange\" style=\"padding-left: 32px;\">
                <span class=\"w3-large\" style=\"font-weight: 400;\">Pre-Production</span>
            </div>
        
            <div class=\"w3-padding w3-bar-block\" style=\"\">
                <a class=\"w3-medium w3-button w3-bar-item action-button\" href=\"/production/\"><i class=\"fa-solid fa-gauge-high w3-text-orange\"></i> &nbsp;&nbsp; Production Dashboard</a>
        
                <a class=\"w3-medium w3-button w3-bar-item action-button\" href=\"/production/classification.php\"><i class=\"fa-solid fa-database w3-text-blue\"></i> &nbsp;&nbsp; Classification</a>
                <a class=\"w3-medium w3-button w3-bar-item action-button\" href=\"/production/article.php\"><i class=\"fa-solid fa-clothes-hanger w3-text-green\"></i> &nbsp;&nbsp; Article</a>
                <a class=\"w3-medium w3-button w3-bar-item action-button\" href=\"/production/worksheet.php\"><i class=\"fa-solid fa-file-lines w3-text-yellow\"></i> &nbsp;&nbsp; Worksheet</a>
            </div>
            ";
        }

    ?>




    <!-- Transaction section -->
    <div class="w3-container w3-padding w3-border-bottom w3-border-top w3-border-orange" style="padding-left: 32px;">
        <span class="w3-large" style="font-weight: 400;">Transaction</span>
    </div>

    <div class="w3-padding w3-bar-block" style="">
        <a class="w3-medium w3-button w3-bar-item action-button" href="/transaction/"><i class="fa-solid fa-gauge-high w3-text-orange"></i> &nbsp;&nbsp; Transaction Dashboard</a>
    </div>

    </div>
