
// popup-timeout.js
// Closes the current window after timeout of "seconds"

// Define the idle time (in milliseconds)
var seconds = 900;

var idleTime = seconds * 1000; // 5 seconds

// Set up a timer
var idleTimer = setTimeout(closeWindow, idleTime);

// Reset the timer when there is user activity
document.addEventListener("mousemove", resetTimer);
document.addEventListener("keypress", resetTimer);

// Function to reset the timer
function resetTimer() {
    clearTimeout(idleTimer);
    idleTimer = setTimeout(closeWindow, idleTime);
}

// Function to close the window
function closeWindow() {
    window.close();
}