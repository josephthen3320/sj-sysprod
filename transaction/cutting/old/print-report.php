<!DOCTYPE html>
<html>
<head>
    <title>Laporan Cutting</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">
</head>

<style>
    @media print {
        #printButton, .hideForPrint {
            display: none;
        }

        @page {
            margin-bottom: 30px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
        }
    }
</style>
<?php session_start() ?>
<?php
$randomString = "cutreport" . date('Ymd') . $_SESSION['username'] ;
$sha256Hash = hash('sha256', $randomString); // Generate the SHA-256 hash
?>

<body class="" style="min-height: 100vh;">

<div class="w3-center w3-light-grey w3-padding">
    <h3>Laporan Cutting</h3>
    <h6 class="w3-monospace w3-tiny">Generated on <span id="genDate"></span> | <?= "{$_SESSION['username']}({$_SESSION['user_id']})" ?><br></h6>
    <button id="printButton" class="w3-round w3-border w3-button w3-bar-item w3-center" onclick="window.print()">
        <i class="fas fa-print"></i> &nbsp;&nbsp; Print
    </button>
</div>

<div class="w3-container">
    <!-- Data Table -->
    <div class="w3-cell-row">
        <h4>Summary</h4>
        <iframe id="frame1" src="report-cutting-by-cmt.php" frameborder="0" style="min-height:;"></iframe>
    </div>
    <div class="w3-cell-row">
        <h4>Hasil Cutting</h4>
        <iframe class="w3-small" id="frame2" src="report-cutting-overview.php" width="100%" frameborder="0"></iframe>
    </div>
</div>

<div class="footer w3-bar w3-center w3-monospace w3-tiny">
    sysprod>><?= $sha256Hash ?>
</div>


<script>
    // Function to adjust the initial iframe heights
    function adjustInitialIframeHeight() {
        var iframes = document.querySelectorAll('iframe');
        for (var i = 0; i < iframes.length; i++) {
            var iframe = iframes[i];
            iframe.style.height = '100px';
            iframe.onload = function() {
                this.style.height = this.contentWindow.document.body.scrollHeight + 'px';
            };
        }
    }
    // Function to adjust the iframe heights when the window is resized
    function adjustIframeHeight() {
        var iframes = document.querySelectorAll('iframe');
        for (var i = 0; i < iframes.length; i++) {
            var iframe = iframes[i];
            iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
        }
    }
    adjustInitialIframeHeight();
    window.addEventListener('resize', adjustIframeHeight);
    // Get the current date and time
    var currentDate = new Date();
    // Format the date and time
    var year = currentDate.getFullYear();
    var month = String(currentDate.getMonth() + 1).padStart(2, '0');
    var day = String(currentDate.getDate()).padStart(2, '0');
    var hours = String(currentDate.getHours()).padStart(2, '0');
    var minutes = String(currentDate.getMinutes()).padStart(2, '0');
    var seconds = String(currentDate.getSeconds()).padStart(2, '0');
    // Construct the formatted date and time string
    var formattedDateTime = year + '.' + month + '.' + day + ' ' + hours + ':' + minutes + ':' + seconds;
    // Display the formatted date and time
    document.getElementById('genDate').textContent = formattedDateTime;
</script>


</body>
</html>