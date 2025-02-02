<?php
include_once('db_connection.php');

// Function to check if ESP32 is reachable using fsockopen
function pingESP32($ip) {
    $port = 80; // Default HTTP port, change if necessary
    $timeout = 1; // Timeout in seconds
    $conn = @fsockopen($ip, $port, $errno, $errstr, $timeout);

    if ($conn) {
        fclose($conn);
        return true; // ESP32 is reachable
    }
    return false; // ESP32 is unreachable
}

// Fetch the latest stored ESP32 status
$sql = "SELECT ssid, ip FROM status ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $ip = $data['ip'] ?? 'N/A';
    $ssid = $data['ssid'] ?? 'N/A';

    // Perform a connectivity test only if an IP is available
    $isConnected = ($ip !== 'N/A' && pingESP32($ip)) ? "Connected" : "Disconnected";

    // If ESP32 is disconnected, hide SSID and IP
    if ($isConnected === "Disconnected") {
        $ssid = "--";
        $ip = "--";
    }

    echo json_encode([
        'ssid' => $ssid,
        'ip' => $ip,
        'status' => $isConnected
    ]);
} else {
    echo json_encode([
        'ssid' => "--",
        'ip' => "--",
        'status' => 'Disconnected'
    ]);
}

$conn->close();
?>
