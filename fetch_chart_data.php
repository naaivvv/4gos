<?php
include_once('db_connection.php');

// Calculate the range dynamically (e.g., latest 20 rows)
$total_rows_to_fetch = 20;

// Query to get the maximum id in the table
$max_id_result = $conn->query("SELECT MAX(id) as max_id FROM `sensor`");
$max_id = $max_id_result->fetch_assoc()['max_id'];
$max_id_result->free();

// Calculate the starting id for the desired range
$start_id = max(1, $max_id - $total_rows_to_fetch + 1);

// Fetch the latest sensor data entries within the calculated range
$sql = "SELECT * FROM `sensor` WHERE `id` BETWEEN $start_id AND $max_id ORDER BY `id` ASC";

$data = [];
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row["id"],
            'sensor_name' => $row["sensor_name"],
            'temp' => $row["temp"],
            'voltage' => $row["voltage"],
            'current' => $row["current"],
            'created_at' => date("h:i:s A", strtotime($row["created_at"])) // Format timestamp
        ];
    }
    $result->free();
}

// Close the database connection
$conn->close();

// Return data as JSON
echo json_encode($data);
?>
