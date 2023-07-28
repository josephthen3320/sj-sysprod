<?php
session_start();

$page_title = "Production Centre | Article";

// Check if the user is not logged in, redirect to login page
include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/verify-session.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";

// MySQL query to fetch information from "users" table for the logged-in user
$username = $_SESSION["username"];
$sql = "SELECT first_name, last_name, employee_id FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, fetch the name and employee_id
    $row = $result->fetch_assoc();
    $name = $row["first_name"] . " " . $row["last_name"];
    $employeeId = $row["employee_id"];
} else {
    // User not found, handle the error
    header("Location: login.php");
}

$top_title = "";
$top_title .= "Article";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/w3.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.css" type="text/css">

    <style>
        body {
            background-color: #F4EDE9;
        }

        .jt-orange {
            background-color: #ff5722;
        }

        .fa-4xl {
            font-size: 3em;
            line-height: 0.01637em;
            vertical-align: -0.27679em;
        }

        #loading {
            display: none;
            width: 100%;
            height: 500px;
            background-color: #f2f2f2;
            text-align: center;
            line-height: 500px;
            font-size: 24px;
            font-weight: bold;
        }

        .article-list {

        }

        .article-detail {

        }
    </style>
</head>
<body>

<!-- Left bar -->
<?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/sidebar.php" ; ?>

<div class="w3-threequarter w3-white sj-content" style="min-height: 100vh; margin-left: 25%;">
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/site-modular/topbar.php" ; ?>

    <div style="margin-top: 16px; min-height: 100vh; background-color: #fbfbfb;">
        <!-- Dashboard menu -->
        <div id="" class="w3-bar w3-white w3-border-bottom " style="display: flex; background-color: #0B293C; height: 72px; align-items: center;">
            <span class="w3-bar-item w3-large" style="color: #0B293C;"><b>Article</b></span>
        </div>
        <div class="w3-bar w3-black">
            <?php if(in_array($_SESSION['user_role'], [0,1,2,5,6])): ?>
            <button class="w3-bar-item w3-button" onclick="openPopupURL2('article/create-article.php')">New Article &nbsp; <i class="fa-solid fa-plus fa-sm"></i> </button>
            <?php endif; ?>
            <div class="w3-bar-item">&nbsp;</div>
        </div>

        <div class="w3-cell-row w3-padding-16">
            <div id="loading">Loading...</div>
            <div class="w3-container w3-twothird w3-padding">
                <iframe id="articleFrame" src="article/article-table.php" frameborder="0" width="100%" style="min-height: 75vh;"></iframe>
            </div>
            <div class="w3-container w3-third w3-padding">
                <iframe class="w3-border w3-border-light-grey" id="articleDetailFrame" src="" width="100%"  frameborder="0" style="min-height: 75vh;"></iframe>
            </div>
        </div>

        <script>// Add event listener to receive messages from the article table iframe
            window.addEventListener("message", function(event) {
                if (event.data && event.data.type === "loadArticleDetail") {
                    loadArticleDetail(event.data.id);
                }
            });

            function loadArticleDetail(id) {
                var articleDetailFrame = document.getElementById("articleDetailFrame");
                articleDetailFrame.src = "article/article-detail.php?id=" + id;
            }
        </script>

        <script>
            var iframe = document.getElementById('articleFrame');
            var loading = document.getElementById('loading');

            function changeIframeSrc(src) {
                showLoadingAnimation();
                iframe.src = src;

                // Store the selected URL in localStorage
                localStorage.setItem('selectedURL', src);
            }

            iframe.addEventListener('load', hideLoadingAnimation);

            function showLoadingAnimation() {
                loading.style.display = 'block';
            }

            function hideLoadingAnimation() {
                loading.style.display = 'none';
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