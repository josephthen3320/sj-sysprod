// Establish WebSocket connection
const socket = new WebSocket('ws://localhost:7574/chat');

// Display received messages in the chatbox
socket.onmessage = function(event) {
    const chatbox = document.getElementById('chatbox');
    chatbox.innerHTML += '<p>' + event.data + '</p>';
    chatbox.scrollTop = chatbox.scrollHeight;
};

// Send message when the "Send" button is clicked
function sendMessage() {
    const messageInput = document.getElementById('message');
    const message = messageInput.value;

    // Send message to the server
    socket.send(message);

    // Clear the input field
    messageInput.value = '';
}
