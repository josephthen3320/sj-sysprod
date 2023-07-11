
<style>
    .jt-dark-blue {
        background-color: #0B293C;
    }
</style>

<script src="/assets/js/utils.js"></script>

<div class="w3-bottom w3-bar w3-hide-medium w3-hide-large w3-text-white" style="background-color: #0B293C; height: 64px;">
    <div class="w3-row" style="height: inherit">
        <button class="w3-col s2 jt-dark-blue w3-hover-blue-grey w3-border-0 w3-text-white" onclick="openURL('/dashboard')" style="height: inherit; padding-top: 16px;">
            <i class="fas fa-home"></i><br>
            <span class="w3-tiny">Home</span>
        </button>
        <button class="w3-col s2 jt-dark-blue w3-hover-blue-grey w3-border-0 w3-text-white" onclick="openURL('#')" style="height: inherit; padding-top: 16px;">
            <i class="fas fa-headset"></i><br>
            <span class="w3-tiny">Support</span>
        </button>

        <div class="w3-col s4 jt-dark-blue w3-border-0 w3-text-white w3-display-container" style="height: inherit; ">
            <div class="w3-display-middle w3-center" onclick="toggleFullscreen()">
                <img src="/assets/logo/sysprod_logo_i.png" height="40">
            </div>

        </div>

        <button class="w3-col s2 jt-dark-blue w3-hover-blue-grey w3-border-0 w3-text-white" onclick="openURL('/production')" style="height: inherit; padding-top: 16px;">
            <i class="fas fa-boxes-stacked"></i><br>
            <span class="w3-tiny">Production</span>
        </button>
        <button class="w3-col s2 jt-dark-blue w3-hover-blue-grey w3-border-0 w3-text-white" onclick="openURL('#')" style="height: inherit; padding-top: 16px;">
            <i class="fas fa-user"></i><br>
            <span class="w3-tiny">Profile</span>
        </button>
    </div>
</div>

<script>
    function toggleFullscreen() {
        if (!document.fullscreenElement && !document.mozFullScreenElement &&
            !document.webkitFullscreenElement && !document.msFullscreenElement) {
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    }
</script>