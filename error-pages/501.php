<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">

    <style>
        h1 {
            font-family: "Titillium Web";
        }

        body {
            background-color: #fbfbfb;
            min-height: 100vh;
        }

        .content {
            -webkit-user-select: none; /* Disable text selection for webkit browsers */
            -moz-user-select: none; /* Disable text selection for Mozilla Firefox */
            -ms-user-select: none; /* Disable text selection for Internet Explorer/Edge */
            user-select: none; /* Disable text selection for other browsers */
            pointer-events: none; /* Disable right-clicking on the element */
        }

        .jt-orange {
            background-color: #ff5722;
        }

        .fa-4xl {
            font-size: 3em;
            line-height: 0.01637em;
            vertical-align: -0.27679em;
        }
    </style>
</head>
<body class="w3-display-container">

<?php

$home_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'];

$lost_list = array(
    "It's not you, it's me!",
    "Not implemented"
);

$home_list = array(
    "Let's wait somewhere else",
    "Let's go home",
    "Back to safety",
    "How about we explore the area ahead of us later?",
    "Should we go back first?"
);

$random_index = array_rand($lost_list);
$msg_lost = $lost_list[$random_index];

$random_index = array_rand($home_list);
$msg_home = $home_list[$random_index] . "&nbsp;&nbsp;";

?>


<div class="w3-display-middle w3-center content">
    <h1 class="w3-text-light-grey w3-hide-small"
        style="font-size: 20vw; font-weight: bolder">
        501
    </h1>
    <h1 class="w3-text-light-grey w3-hide-large w3-hide-medium"
        style="font-size: 40vw; font-weight: bolder">
        501
    </h1>
</div>
<div class="w3-display-middle w3-center content">
    <h1 class="w3-text-grey w3-hide-small" style="font-size: 2vw; font-weight: bolder; z-index: 10;">
        <?= $msg_lost ?>
    </h1>

    <h1 class="w3-text-grey w3-hide-large w3-hide-medium" style="font-size: 8vw; font-weight: bolder; z-index: 10;">
        <?= $msg_lost ?>
    </h1>
</div>

<div class="w3-display-middle w3-center" style="margin-top: 24vh">
    <h1 class="w3-button w3-light-grey w3-padding-16 w3-text-grey w3-hide-small"
        onclick="openURL('<?= $home_url ?>')"
        style="margin-top: 00vh; font-size: 1vw; font-weight: bolder; z-index: 10; width: 35vw;">
        <?= $msg_home ?><i class="fa-solid fa-arrow-right-long"></i>
    </h1>
    <h1 class="w3-button w3-light-grey w3-padding-16 w3-text-grey w3-hide-large w3-hide-medium"
        onclick="openURL('<?= $home_url ?>')"
        style="margin-top: 12vh; font-size: 4vw; font-weight: bolder; z-index: 10; width: 70vw;">
        <?= $msg_home ?><i class="fa-solid fa-arrow-right-long"></i>
    </h1>
</div>

</body>
</html>

<script src="/assets/js/utils.js"></script>