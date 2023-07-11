<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cutting Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">

    <style>
    </style>
</head>
<body>

        <!-- Data Table -->
        <div class="w3-cell-row">
            <h3>Summary</h3>
            <iframe id="frame1" src="report-outflow-pivot.php" width="50%" frameborder="0" style="min-height: ;"></iframe>
        </div>

        <div class="w3-cell-row">
            <h3>Rekap Outflow</h3>
            <iframe id="frame2" src="report-outflow.php" width="100%" frameborder="0"></iframe>
        </div>

        <script>
            // Get all iframe elements
            var iframes = document.querySelectorAll('iframe');

            // Adjust the height for each iframe
            for (var i = 0; i < iframes.length; i++) {
                var iframe = iframes[i];

                // Set the height when the iframe content loads
                iframe.onload = function() {
                    // Set the iframe height to the content's height
                    this.style.height = this.contentWindow.document.body.scrollHeight + 'px';
                };
            }

        </script>

        <div class="w3-container w3-cell-row w3-padding-16">

        </div>

    </div>
</div>

<script>
    function openPopup(url, name) {
        var windowFeatures = "width=400,height=700,top=100,left=200,resizable=no,scrollbars=no,toolbar=no,menubar=no,location=no,status=no";

        window.open(url, name, windowFeatures);
    }

    function openURL(url) {
        window.location.href = url;
    }

    function openTabURL(url, target) {
        window.open(url, target);
    }

    function dropdown(id) {
        var x = document.getElementById(id);
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }


</script>

</body>
</html>