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
    "It's not me, it's you!",
    "You're lost",
    "You broke the internet.. maybe",
    "Can't find your page",
    "Where am I?",
    "There's nothing here"
);

$home_list = array(
    "Retrace your step",
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
        404
    </h1>
    <h1 class="w3-text-light-grey w3-hide-large w3-hide-medium"
        style="font-size: 40vw; font-weight: bolder">
        404
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
    <h1 class="w3-button w3-light-grey w3-padding-16 w3-text-grey w3-hide-small w3-half"
        onclick="history.back();"
        style="margin-top: 00vh; font-size: 1vw; font-weight: bolder; z-index: 10; width: 15vw; margin-right: 1vw">
        <i class="fa-solid fa-arrow-left-long"></i><?= "&nbsp; Go back" ?>
    </h1>

    <h1 class="w3-button w3-light-grey w3-padding-16 w3-text-grey w3-hide-small w3-half"
        onclick="openURL('<?= $home_url ?>')"
        style="margin-top: 00vh; font-size: 1vw; font-weight: bolder; z-index: 10; width: 15vw; margin-left: 1vw">
        <?= $msg_home ?><i class="fa-solid fa-house"></i>
    </h1>
    <h1 class="w3-button w3-light-grey w3-padding-16 w3-text-grey w3-hide-large w3-hide-medium w3-half"
        onclick="openURL('<?= $home_url ?>')"
        style="margin-top: 12vh; font-size: 4vw; font-weight: bolder; z-index: 10; width: 70vw;">
        <?= $msg_home ?><i class="fa-solid fa-arrow-right-long"></i>
    </h1>
</div>

</body>
</html>

<script src="/assets/js/utils.js"></script>