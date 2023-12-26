<!DOCTYPE html>
<html>
<head>
    <title>Real-Time Chat</title>
    <style>
        #chatbox {
            width: 400px;
            height: 300px;
            overflow-y: scroll;
        }
    </style>
</head>
<body>
<h1>Real-Time Chat</h1>

<div id="chatbox"></div>

<input type="text" id="message" placeholder="Type your message">
<button onclick="sendMessage()">Send</button>

<script src="script.js"></script>
</body>
</html>
