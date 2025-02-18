<?php
include_once('db_connection.php');

// Function to check if ESP32 is reachable using fsockopen
function isESP32Connected($ip) {
    $port = 80; // HTTP port
    $timeout = 1; // Timeout in seconds
    $conn = @fsockopen($ip, $port, $errno, $errstr, $timeout);

    if ($conn) {
        fclose($conn);
        return true; // ESP32 is reachable
    }
    return false; // ESP32 is unreachable
}

// Fetch the latest ESP32 IP from the database
$sql = "SELECT ip FROM status ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $espData = $result->fetch_assoc();
    $espIp = $espData['ip'] ?? 'N/A';

    // Check ESP32 connectivity
    if ($espIp !== 'N/A' && isESP32Connected($espIp)) {
        // If ESP32 is connected, fetch sensor data
        $sql = "SELECT temp, kwh, is_fan_on, voltage, current FROM sensor ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $sensorData = $result->fetch_assoc();
            echo json_encode([
                'temp' => $sensorData['temp'],
                'kwh' => $sensorData['kwh'],
                'is_fan_on' => $sensorData['is_fan_on'],
                'voltage' => $sensorData['voltage'],
                'current' => $sensorData['current'],
                'status' => 'Connected'
            ]);
            exit;
        }
    }
}

// If ESP32 is disconnected, return empty values
echo json_encode([
    'temp' => '--',
    'kwh' => '----',
    'is_fan_on' => '--',
    'voltage' => '--',
    'current' => '--',
    'status' => 'Disconnected'
]);

$conn->close();
?>
