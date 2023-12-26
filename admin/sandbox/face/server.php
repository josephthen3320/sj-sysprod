<?php
// Create WebSocket server
$server = new WebSocketServer('localhost', 7574, 'chat');

// Handle incoming messages
$server->onMessage = function($client, $message) use ($server) {
    // Broadcast the message to all connected clients
    $server->broadcast($message);
};

// Run the WebSocket server
$server->run();

class WebSocketServer {
    private $clients = [];
    private $master;
    private $name;

    public function __construct($host, $port, $name) {
        $this->name = $name;

        // Create a master WebSocket socket
        $this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($this->master, $host, $port);
        socket_listen($this->master);

        // Add the master socket to the clients array
        $this->clients[] = $this->master;

        echo "WebSocket server '{$this->name}' started on {$host}:{$port}\n";
    }

    public function run() {
        while (true) {
            // Create a copy of the clients array to use in the socket_select function
            $read = $this->clients;
            $write = null;
            $except = null;

            // Wait for activity on any of the connected sockets
            socket_select($read, $write, $except, null);

            foreach ($read as $socket) {
                // If it's the master socket, a new client is connecting
                if ($socket === $this->master) {
                    $client = socket_accept($this->master);
                    $this->clients[] = $client;
                    $this->sendWelcomeMessage($client);
                    echo "Client connected\n";
                } else {
                    // Handle the client's data
                    $bytes = socket_recv($socket, $buffer, 2048, 0);
                    if ($bytes === 0) {
                        // The client has disconnected
                        $this->disconnectClient($socket);
                    } else {
                        $this->onMessage($socket, $buffer);
                    }
                }
            }
        }
    }

    public function onMessage($client, $message) {
        // Implement your custom message handling logic here
        // This is just a basic example that broadcasts the message to all connected clients
        $this->broadcast($message);
    }

    public function broadcast($message) {
        foreach ($this->clients as $client) {
            if ($client !== $this->master) {
                socket_write($client, $message, strlen($message));
            }
        }
    }

    public function sendWelcomeMessage($client) {
        $message = "Welcome to the {$this->name}! Start chatting!";
        socket_write($client, $message, strlen($message));
    }

    public function disconnectClient($client) {
        $index = array_search($client, $this->clients);
        socket_close($client);
        unset($this->clients[$index]);
        echo "Client disconnected\n";
    }
}

?>