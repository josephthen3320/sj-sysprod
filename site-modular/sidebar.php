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
        margin-left: 33.3%;
    }


    @media only screen and (max-width: 1000px) {
        .sj-content {
            margin-left: 0 !important;
            margin-bottom: 64px;
            width: 100vw !important;
        }
    }

    .nav-scrollbar::-webkit-scrollbar {
        width: 2px;
        opacity:0;
        transition: opacity 0.3s;
    }
    .nav-scrollbar::-webkit-scrollbar-thumb {
        background-color: #9c9794;
    }
    .nav-scrollbar:hover::-webkit-scrollbar,
    .nav-scrollbar::-webkit-scrollbar-thumb:active {
        opacity: 1;
    }
</style>

<!-- Side navigation bar -->
<div class="w3-sidebar w3-bar-block w3-quarter w3-text-white w3-hide-small w3-hide-medium nav-scrollbar" style="min-height: 100vh; padding-bottom: 20vh; position: fixed; background-color: #0B293C">
    <!-- Header -->
    <div class="w3-container w3-padding" style="display:flex; justify-content: center; align-items: center; width: 100%; height: 100px;">
            <img src="/assets/logo/sysprod_logo_w.png" height="80%">
    </div>

    <!-- General section -->
    <div class="w3-container w3-padding w3-border-bottom w3-border-top w3-border-orange" style="padding-left: 32px;">
        <span class="w3-large" style="font-weight: 400;">General</span>
    </div>

    <div class="w3-padding w3-bar-block" style="">
        <a class="w3-medium w3-button w3-bar-item action-button" href="/dashboard/"><i class="fas fa-fw fa-gauge-high w3-text-orange"></i> &nbsp;&nbsp; Dashboard</a>
        <a class="w3-medium w3-button w3-bar-item action-button" href="/support/"><i class="fas fa-fw fa-headset w3-text-red"></i> &nbsp;&nbsp; Support</a>
    </div>

    <!-- Admin Zone -->
    <?php
    if(in_array($_SESSION['user_role'], [0,1])) {
        echo "<div class='w3-container w3-padding w3-border-bottom w3-border-top w3-border-orange' style='padding-left: 32px;'>";
        echo "    <span class='w3-large' style='font-weight: 400;'>Admin</span>";
        echo "</div>";
    }
    ?>
    <div class="w3-padding w3-bar-block" style="">
    <?php
    $userManagementButton = '<a class="w3-medium w3-button w3-bar-item action-button" href="/admin/user-management"><i class="fas fa-fw fa-user w3-text-red"></i> &nbsp;&nbsp; User Management</a>';
    $activityLogButton = '<a class="w3-medium w3-button w3-bar-item action-button" href="/admin/activities"><i class="fas fa-fw fa-list-timeline w3-text-red"></i> &nbsp;&nbsp; Activity Log</a>';
    $announcementButton   = '<a class="w3-medium w3-button w3-bar-item action-button" href="/admin/announcement"><i class="fas fa-fw fa-megaphone w3-text-red"></i> &nbsp;&nbsp; Announcement</a>';
    $systemsButton   = '<a class="w3-medium w3-button w3-bar-item action-button" href="/admin/systems/manage.php"><i class="fas fa-fw fa-computer w3-text-red"></i> &nbsp;&nbsp; Systems</a>';

    echo ($_SESSION['user_role'] <= 1) ? $userManagementButton : "";
    echo ($_SESSION['user_role'] <= 1) ? $activityLogButton : "";
    echo ($_SESSION['user_role'] <= 1) ? $announcementButton : "";
    echo ($_SESSION['user_role'] <= 1) ? $systemsButton : "";

    ?>
    </div>

    <!-- Production section -->
    <?php
    if (in_array($_SESSION['user_role'], [0, 1, 2, 3, 4, 5, 6, 7, 10])) {
        if ($_SESSION['user_role'] != 4) {
            echo "
        <div class=\"w3-container w3-padding w3-border-bottom w3-border-top w3-border-orange\" style=\"padding-left: 32px;\">
            <span class=\"w3-large\" style=\"font-weight: 400;\">Pre-Production</span>
        </div>
    
        <div class=\"w3-padding w3-bar-block\" style=\"\">
        ";
            // echo "<a class=\"w3-medium w3-button w3-bar-item action-button\" href=\"/production/\"><i class=\"fas fa-fw fa-gauge-high w3-text-orange\"></i> &nbsp;&nbsp; Production Dashboard</a>";

            if (in_array($_SESSION['user_role'], [0, 1, 2, 5, 6, 10])) {
                echo "<a class=\"w3-medium w3-button w3-bar-item action-button\" href=\"/production/classification.php\"><i class=\"fas fa-fw fa-database w3-text-blue\"></i> &nbsp;&nbsp; Classification</a>";
            }

            if (in_array($_SESSION['user_role'], [0, 1, 2, 3, 4, 5, 6, 7, 10])) {
                echo "<a class=\"w3-medium w3-button w3-bar-item action-button\" href=\"/production/article.php\"><i class=\"fas fa-fw fa-clothes-hanger w3-text-green\"></i> &nbsp;&nbsp; Article</a>";
            }

            if (in_array($_SESSION['user_role'], [0, 1, 2, 3, 4, 5, 6, 7, 10])) {
                echo "<a class=\"w3-medium w3-button w3-bar-item action-button\" href=\"/production/worksheet.php\"><i class=\"fas fa-fw fa-file-lines w3-text-yellow\"></i> &nbsp;&nbsp; Worksheet</a>";
            }

            echo "
        </div>
        ";
        }
    }
    ?>



    
    <!-- Transaction section -->
    <?php
    if(in_array($_SESSION['user_role'], [0,1,2,3,4,5,6,7,10])) {
        echo "<div class='w3-container w3-padding w3-border-bottom w3-border-top w3-border-orange' style='padding-left: 32px;'>";
        echo "    <span class='w3-large' style='font-weight: 400;'>Transaction</span>";
        echo "</div>";

        echo "<div class='w3-padding w3-bar-block' style=''>";
        echo "    <a class='w3-medium w3-button w3-bar-item action-button' href='/transaction/'><i class='fas fa-fw fa-gauge-high w3-text-orange'></i> &nbsp;&nbsp; Transaction Dashboard</a>";
        echo "    <a class='w3-medium w3-button w3-bar-item action-button' href='/warehouse'><i class='fas fa-fw fa-warehouse-full w3-text-blue'></i> &nbsp;&nbsp; Warehouse</a>";
        echo "</div>";
    }
    ?>

    <!-- Reporting section -->
    <?php
    if(in_array($_SESSION['user_role'], [0,1,2,3,4,5,6,7,10])) {
        echo "<div class='w3-container w3-padding w3-border-bottom w3-border-top w3-border-orange' style='padding-left: 32px;'>";
        echo "    <span class='w3-large' style='font-weight: 400;'>Reporting</span>";
        echo "</div>";

        echo "<div class='w3-padding w3-bar-block' style=''>";
        echo "    <a class='w3-medium w3-button w3-bar-item action-button' href='/reporting/'><i class='fas fa-fw fa-chart-mixed w3-text-orange'></i> &nbsp;&nbsp; Reporting Dashboard</a>";
        echo "</div>";
    }
    ?>

    </div>
