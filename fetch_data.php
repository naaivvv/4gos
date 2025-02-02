<?php
include_once('db_connection.php');

// Fetch the latest 500 sensor data entries
$sql = "SELECT * FROM `sensor` ORDER BY id DESC LIMIT 10";

// Execute the query and prepare data for Chart.js
$data = [];
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row["id"],
            'sensor_name' => $row["sensor_name"],
            'temp' => $row["temp"],
            'is_fan_on' => $row["is_fan_on"],
        ];
    }
    // Free the result set
    $result->free();
}

// Close the database connection
$conn->close();

// Return data as JSON
echo json_encode($data);
?>