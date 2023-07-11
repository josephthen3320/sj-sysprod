<?php
    header("Location: dashboard");
?>


<!DOCTYPE html>
<html>
<head>
	<title>CV Subur Jaya</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">

    <script src="/assets/js/utils.js"></script>

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background-color: #e1e1e1;
        }
        a {
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="w3-top w3-bar w3-white" style="display: flex; align-items: center; ">
        <div class="w3-bar-item w3-hide-small">
            <img src="assets/logo/SJ_Logo.png" width="48px">
        </div>
        <div class="w3-bar-item w3-large" style="font-weight: bold;">CV Subur Jaya</div>

        <div class="w3-bar-item w3-container w3-button" onclick="openURL('login.php')"
             style="display: flex; height: 64px ; right:0; position: absolute; align-items: center">
            Masuk &nbsp; <i class="fa-solid fa-right-to-bracket"></i>
        </div>
    </div>




    <footer class="w3-bottom w3-dark-grey">
        <div class="w3-padding-32 w3-hide-small w3-hide-medium" style="padding-left: 256px; padding-right: 128px;">
            <div class="w3-half w3-container">
                <div class="w3-rest">
                    <img src="assets/logo/SJ_Logo.png" width="48px" style="cursor: pointer;" onclick="openURL('<?= "http://" . $_SERVER['HTTP_HOST']; ?>')">
                </div>
                <div class="w3-rest">
                    <h6><b>Go Make Something Awesome!</b></h6>
                    <p>CV. Subur Jaya is a fashion company that creates high quality garment products with carefully sourced materials.</p>
                    <p>Proudly made in <a class="w3-hover-text-red" href="https://www.google.com/maps/place/Indonesia">Indonesia</a>, with <i class="fa-solid fa-heart w3-text-red"></i> </p>
                </div>
            </div>
            <div class="w3-half w3-container">
                <h6 style=""><b>Get in touch with us</b></h6>
                <a class="w3-hover-text-blue" href="https://goo.gl/maps/6BYMVMH2RNSScXbF8">
                <p><i class="fa-solid fa-location-dot"></i>&nbsp;&nbsp;&nbsp;&nbsp; Jl. Mochammad Ramdan No. 56, Bandung</p>
                </a>
                <p><i class="fa-solid fa-calendar"></i>&nbsp;&nbsp;&nbsp;&nbsp; Monday - Saturday</p>
                <p><i class="fa-solid fa-clock"></i>&nbsp;&nbsp;&nbsp;&nbsp; 07:30 - 16:30 (UTC+7)</p>
                <p><i class="fa-solid fa-brands fa-whatsapp"></i>&nbsp;&nbsp;&nbsp;&nbsp; +62 821 0505 0505</p>

            </div>
        </div>

        <div class="w3-padding-32 w3-hide-large" style="padding-left: 16px; padding-right: 16px">
            <div class="w3-half w3-container">
                <div class="w3-rest">
                    <img src="assets/logo/SJ_Logo.png" width="48px" style="cursor: pointer;" onclick="openURL('<?= "http://" . $_SERVER['HTTP_HOST']; ?>')">
                </div>
                <div class="w3-rest">
                    <h6><b>Go Make Something Awesome!</b></h6>
                    <p>CV. Subur Jaya is a fashion company that creates high quality garment products with carefully sourced materials.</p>
                    <p>Proudly made in <a class="w3-hover-text-red" href="https://www.google.com/maps/place/Indonesia" style="text-decoration: none;">Indonesia</a>, with <i class="fa-solid fa-heart w3-text-red"></i> </p>
                </div>
            </div>
            <div class="w3-half w3-container">
                <p><i class="fa-solid fa-location-dot"></i>&nbsp;&nbsp;&nbsp;&nbsp; Jl. Mochammad Ramdan No. 56, Bandung</p>
                <p><i class="fa-solid fa-calendar"></i>&nbsp;&nbsp;&nbsp;&nbsp; Monday - Saturday</p>
                <p><i class="fa-solid fa-clock"></i>&nbsp;&nbsp;&nbsp;&nbsp; 07:30 - 16:30 (UTC+7)</p>
                <p><i class="fa-solid fa-brands fa-whatsapp"></i>&nbsp;&nbsp;&nbsp;&nbsp; +62 821 0505 0505</p>

            </div>
        </div>

        <div class="w3-bar w3-center w3-padding-16 w3-tiny w3-black">
            &copy; 2023 CV Subur Jaya | All rights reserved.
        </div>
    </footer>

</body>
</html>
