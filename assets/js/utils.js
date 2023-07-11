function goBack() {
    window.history.back();
}

function openURL(url) {
    window.location.href = url;
}

function openPopupURL(url, windowName, width, height) {
    var windowFeatures = "width=" + width + ",height=" + height + ",top=100,left=200,resizable=no,scrollbars=no,toolbar=no,menubar=no,location=no,status=no";
    window.open(url, windowName, windowFeatures);

}

// Reloads the source page after popup is closed
function openPopupURL2(url, windowName, width, height) {
    console.log("Window opened" + windowName);

    var popupWindow = window.open(url, windowName, "width=" + width + ",height=" + height + ",top=100,left=200,resizable=no,scrollbars=no,toolbar=no,menubar=no,status=no");

    var popupChecker = setInterval(function() {
        if (popupWindow.closed) {
            location.reload();
            clearInterval(popupChecker);
        }
    }, 100);
}

function openPopupURL35(url, windowName) {
    var screenWidth = window.screen.width;
    var screenHeight = window.screen.height;

    var width = Math.floor(screenWidth * 0.5);
    var height = Math.floor(screenHeight * 0.5);

    var left = Math.floor((screenWidth/2) - (screenWidth/2));
    var top = Math.floor((screenHeight/2) / (screenHeight/2));

    var windowFeatures = "width=" + width + ",height=" + height + ",top=" + top + ",left=" + left + ",resizable=no,scrollbars=no,toolbar=no,menubar=no,location=no,status=no";

    window.open(url, windowName, windowFeatures);
}