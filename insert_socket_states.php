<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    http_response_code(200);
    exit();
}

// Function to check if ESP32 is reachable
function pingESP32($ip) {
    $port = 80; // Default HTTP port
    $timeout = 1; // Timeout in seconds
    $conn = @fsockopen($ip, $port, $errno, $errstr, $timeout);

    if ($conn) {
        fclose($conn);
        return true; // ESP32 is reachable
    }
    return false; // ESP32 is unreachable
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode JSON input
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    // Debugging: Log received data
    file_put_contents('debug.log', print_r($input, true));

    if (!$input) {
        echo json_encode(["error" => "Invalid JSON received"]);
        exit();
    }

    $s1 = isset($input['s1']) ? intval($input['s1']) : 0;
    $s2 = isset($input['s2']) ? intval($input['s2']) : 0;
    $s3 = isset($input['s3']) ? intval($input['s3']) : 0;
    $s4 = isset($input['s4']) ? intval($input['s4']) : 0;

    // Fetch the latest stored ESP32 IP
    $sql = "SELECT ip FROM status ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $ip = $data['ip'] ?? '';

        // Check if ESP32 is reachable
        if ($ip && pingESP32($ip)) {
            // Fetch the last recorded state
            $sql_check = "SELECT s1, s2, s3, s4 FROM socket ORDER BY id DESC LIMIT 1";
            $result_check = $conn->query($sql_check);

            $lastState = $result_check->fetch_assoc();

            // Compare with the new states
            if ($lastState && 
                $lastState['s1'] == $s1 &&
                $lastState['s2'] == $s2 &&
                $lastState['s3'] == $s3 &&
                $lastState['s4'] == $s4) {
                
                echo json_encode(["message" => "No changes detected, skipping insertion."]);
            } else {
                // Insert new state only if it is different
                $sql_insert = "INSERT INTO socket (s1, s2, s3, s4) VALUES ('$s1', '$s2', '$s3', '$s4')";
                
                if ($conn->query($sql_insert) === TRUE) {
                    echo json_encode(["message" => "Data inserted successfully."]);
                } else {
                    echo json_encode(["error" => "Database Error: " . $conn->error]);
                }
            }
        } else {
            echo json_encode(["error" => "ESP32 is unreachable, data not inserted."]);
        }
    } else {
        echo json_encode(["error" => "No IP found in database, data not inserted."]);
    }
}

$conn->close();
?>
