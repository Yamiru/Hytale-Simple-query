<?php

/**
 * Hytale Server Query Tool
 * Uses the Query protocol to fetch server information via UDP
 */

$host    = "127.0.0.1;
$port    = 5523;
$timeout = 3;

/**
 * Query a Hytale server using the Query protocol
 *
 * @param string $host    Server IP address
 * @param int    $port    Query port
 * @param int    $timeout Connection timeout in seconds
 * @return array|false    Server info array or false on failure
 */
function hytaleQuery(string $host, int $port, int $timeout = 3): array|false
{
    $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_set_option($sock, SOL_SOCKET, SO_RCVTIMEO, ['sec' => $timeout, 'usec' => 0]);

    $sessionId = rand(1, 0x0FFFFFFF);

    // Send handshake packet
    $handshake = pack('n', 0xFEFD) . chr(0x09) . pack('N', $sessionId);
    socket_sendto($sock, $handshake, strlen($handshake), 0, $host, $port);

    $response = '';
    if (!socket_recvfrom($sock, $response, 256, 0, $from, $fromPort)) {
        socket_close($sock);
        return false;
    }

    // Parse challenge token from handshake response
    $challenge = (int) rtrim(substr($response, 5), "\x00");

    // Send full stat request
    $statRequest = pack('n', 0xFEFD) . chr(0x00) . pack('N', $sessionId) . pack('N', $challenge) . pack('N', 0);
    socket_sendto($sock, $statRequest, strlen($statRequest), 0, $host, $port);

    $response = '';
    if (!socket_recvfrom($sock, $response, 4096, 0, $from, $fromPort)) {
        socket_close($sock);
        return false;
    }

    socket_close($sock);

    // Skip 16-byte header and parse key/value pairs
    $data  = substr($response, 16);
    $parts = explode("\x00\x00\x01player_\x00\x00", $data);
    $pairs = explode("\x00", $parts[0]);

    $info = [];
    for ($i = 0; $i < count($pairs) - 1; $i += 2) {
        $key = $pairs[$i];
        $val = $pairs[$i + 1];
        if ($key !== '') {
            $info[$key] = $val;
        }
    }

    return $info;
}

// Execute query
$info = hytaleQuery($host, $port, $timeout);

if (!$info) {
    echo "Server is offline or not responding to queries.";
    exit;
}

// Display results
$serverName = $info['hostname']   ?? 'Unknown';
$map        = $info['map']        ?? 'Unknown';
$players    = $info['numplayers'] ?? 0;
$maxPlayers = $info['maxplayers'] ?? 0;

echo "<pre style='font-family: monospace; background: #1a1a2e; color: #eee; padding: 15px; border-radius: 8px;'>";
echo "Server Name: {$serverName}\n";
echo "Map:         {$map}\n";
echo "Players:     {$players} / {$maxPlayers}\n";
echo "</pre>";
